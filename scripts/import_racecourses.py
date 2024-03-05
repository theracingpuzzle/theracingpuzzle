import sqlite3
import csv

# Function to create the table if it doesn't exist
def create_table(cursor):
    cursor.execute('''CREATE TABLE IF NOT EXISTS Racecourses (
                        id INTEGER PRIMARY KEY,
                        name TEXT NOT NULL
                    )''')

# Function to insert data from CSV into the SQLite database
def import_data(cursor, conn):
    with open('Racecourse.csv', 'r', newline='') as csvfile:
        csvreader = csv.reader(csvfile)
        next(csvreader)  # Skip header row
        for row in csvreader:
            cursor.execute('INSERT INTO Racecourses (name) VALUES (?)', (row[0],))
    conn.commit()

# Connect to SQLite database
conn = sqlite3.connect('theracinghub.db')
cursor = conn.cursor()

# Create the table
create_table(cursor)

# Import data from CSV
import_data(cursor, conn)

# Close connection
conn.close()

print("Data imported successfully!")
