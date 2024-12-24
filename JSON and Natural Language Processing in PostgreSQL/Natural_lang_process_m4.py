import requests
import psycopg2
from psycopg2.extras import Json

# Database credentials
db_params = {
    "host": "pg.pg4e.com",
    "port": 5432,
    "database": "pg4e_60af0659a6",
    "user": "pg4e_60af0659a6",
    "password": "pg4e_p_1a1f64be5bd65e1",  # Replace with the actual password
}

# Function to fetch Pokémon data
def fetch_pokemon_data(pokemon_id):
    url = f"https://pokeapi.co/api/v2/pokemon/{pokemon_id}"
    response = requests.get(url)
    response.raise_for_status()  # Raise HTTPError for bad responses
    return response.json()

# Main function to populate the database
def populate_pokemon_table():
    try:
        # Connect to the PostgreSQL database
        conn = psycopg2.connect(**db_params)
        cursor = conn.cursor()

        # Create table if it does not exist
        cursor.execute("""
            CREATE TABLE IF NOT EXISTS pokeapi (
                id INTEGER PRIMARY KEY,
                body JSONB
            )
        """)
        conn.commit()

        # Insert Pokémon data
        for pokemon_id in range(1, 101):  # Loop from 1 to 100
            print(f"Fetching Pokémon ID: {pokemon_id}")
            pokemon_data = fetch_pokemon_data(pokemon_id)
            
            # Insert into database
            cursor.execute("""
                INSERT INTO pokeapi (id, body)
                VALUES (%s, %s)
                ON CONFLICT (id) DO NOTHING
            """, (pokemon_id, Json(pokemon_data)))

        # Commit changes
        conn.commit()
        print("Data successfully inserted!")

    except Exception as e:
        print(f"Error: {e}")
    
    finally:
        # Close the database connection
        if conn:
            cursor.close()
            conn.close()

if __name__ == "__main__":
    populate_pokemon_table()
