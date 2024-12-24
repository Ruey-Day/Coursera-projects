import psycopg2

# Database connection details
db_host = "pg.pg4e.com"
db_port = 5432
db_name = "pg4e_60af0659a6"
db_user = "pg4e_60af0659a6"
db_password = "pg4e_p_1a1f64be5bd65e1"  # Replace with your actual password

# Function to generate pseudo-random numbers
def generate_sequence():
    sequence = []
    value = 852316
    for i in range(300):
        sequence.append((i + 1, value))
        value = int((value * 22) / 7) % 1000000
    return sequence

# Connect to the database
try:
    conn = psycopg2.connect(
        host=db_host,
        port=db_port,
        database=db_name,
        user=db_user,
        password=db_password
    )
    cur = conn.cursor()

    # Drop the table if it exists
    cur.execute("DROP TABLE IF EXISTS pythonseq;")
    conn.commit()

    # Create the table
    cur.execute("CREATE TABLE pythonseq (iter INTEGER, val INTEGER);")
    conn.commit()

    # Generate and insert the sequence
    sequence = generate_sequence()
    cur.executemany("INSERT INTO pythonseq (iter, val) VALUES (%s, %s);", sequence)
    conn.commit()

    print("Data inserted successfully.")

except Exception as e:
    print("Error:", e)

finally:
    if 'cur' in locals():
        cur.close()
    if 'conn' in locals():
        conn.close()
