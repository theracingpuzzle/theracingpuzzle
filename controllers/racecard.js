// Function to fetch racecard data from the API
async function fetchData() {
    const options = {
        method: 'GET',
        url: 'https://horse-racing.p.rapidapi.com/racecards',
        params: { date: '2024-03-12' },
        headers: {
            'X-RapidAPI-Key': '05af7738fdmshb82b9d247e55d09p1b4ffejsnd7f4175f5c9b',
            'X-RapidAPI-Host': 'horse-racing.p.rapidapi.com'
        }
    };

    try {
        const response = await axios.request(options);
        return response.data;
    } catch (error) {
        console.error(error);
        return null;
    }
}

// Function to display racecard using fetched data
function displayRacecard(racecardData) {
    const racecardContainer = document.getElementById('racecard-info');
    if (!racecardContainer) return; // Check if the container exists

    racecardContainer.innerHTML = '';

    racecardData.forEach(race => {
        const raceElement = document.createElement('div');
        raceElement.classList.add('race');

        const titleElement = document.createElement('h3');
        titleElement.textContent = race.title;

        const courseElement = document.createElement('p');
        courseElement.textContent = `Course: ${race.course}`;

        const dateElement = document.createElement('p');
        dateElement.textContent = `Date: ${race.date}`;

        const distanceElement = document.createElement('p');
        distanceElement.textContent = `Distance: ${race.distance}`;

        const ageElement = document.createElement('p');
        ageElement.textContent = `Age: ${race.age}`;

        const goingElement = document.createElement('p');
        goingElement.textContent = `Going: ${race.going}`;

        const prizeElement = document.createElement('p');
        prizeElement.textContent = `Prize: ${race.prize}`;

        const classElement = document.createElement('p');
        classElement.textContent = `Class: ${race.class}`;

        raceElement.appendChild(titleElement);
        raceElement.appendChild(courseElement);
        raceElement.appendChild(dateElement);
        raceElement.appendChild(distanceElement);
        raceElement.appendChild(ageElement);
        raceElement.appendChild(goingElement);
        raceElement.appendChild(prizeElement);
        raceElement.appendChild(classElement);

        // Set the raceId as a data attribute
        raceElement.dataset.raceId = race.id_race; // Adjusted to use id_race

        racecardContainer.appendChild(raceElement);
    });
}

// Function to load racecard data from local storage or fetch it from API
async function loadRacecardData() {
    let racecardData = localStorage.getItem('racecardData');
    if (!racecardData) {
        racecardData = await fetchData();
        localStorage.setItem('racecardData', JSON.stringify(racecardData));
    } else {
        racecardData = JSON.parse(racecardData);
    }
    return racecardData;
}

// Function to load racecourses from racecard data
function loadRacecourses(racecardData) {
    const courseList = document.getElementById('course-list');
    console.log(courseList); // Add logging statement here
    if (!courseList) return; // Check if the list element exists

    const courses = [...new Set(racecardData.map(race => race.course))];
    courses.forEach(course => {
        const listItem = document.createElement('li');
        listItem.textContent = course;
        courseList.appendChild(listItem);
    });
}

// Initialize the racecard
async function initializeRacecard() {
    console.log('Initializing racecard...');
    const racecardData = await loadRacecardData();
    if (racecardData) {
        displayRacecard(racecardData);
        loadRacecourses(racecardData);
    }
}

// Call the initializeRacecard function
initializeRacecard();

// Event listener for race clicks using event delegation
document.addEventListener('click', async (event) => {
    const raceElement = event.target.closest('.race');
    if (raceElement) {
        const raceId = raceElement.dataset.raceId;
        console.log('Clicked on race with raceId:', raceId); // Debugging statement
        if (!raceId) {
            console.error('RaceId is undefined');
            return;
        }
        console.log('Redirecting to runners.html?raceId=' + raceId); // Debugging statement
        // Redirect to runners.html passing the raceId
        window.location.href = `runners.html?raceId=${raceId}`;
    }
    else {
        console.log('No race element clicked');
    }
});



