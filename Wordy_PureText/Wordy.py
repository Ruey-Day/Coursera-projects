class Letter:
    def __init__(self, character: str):
        self.letter = character
        self.character = character
        self.in_correct_place = False
        self.in_word = False  
        
    def is_in_correct_place(self) -> bool:
        return self.in_correct_place

    def is_in_word(self) -> bool:
        return self.in_word 
class Bot:
    def __init__(self, word_list_file: str):
        self.previous_guesses = []
        self.known_correct_positions = [None] * 5 
        self.excluded_letters = set()

        with open(word_list_file, 'r') as file:
            self.word_list = [line.strip().upper() for line in file.readlines()]

        self.possible_words = self.word_list[:]
    def make_guess(self) -> str:
        if not self.possible_words:
            raise ValueError("No possible words to guess from. Did you load a word list?")
        
        guess = random.choice(self.possible_words)
        while guess in self.previous_guesses:
            guess = random.choice(self.possible_words)
        
        self.previous_guesses.append(guess)
        return guess
    def record_guess_results(self, guess: str, results: list):
        for i, letter_obj in enumerate(results):
            if letter_obj.is_in_correct_place():
                self.known_correct_positions[i] = letter_obj.letter
            if letter_obj.is_in_word():  
                continue  
            else:
                self.excluded_letters.add(letter_obj.letter)
        self.possible_words = [
            word for word in self.word_list
            if all(
                (self.known_correct_positions[i] is None or word[i] == self.known_correct_positions[i]) and
                all(letter not in word for letter in self.excluded_letters)
                for i in range(5)
            )
        ]
import random
class GameEngine:
    def __init__(self):
        self.err_input = False
        self.err_guess = False
        self.prev_guesses = []  
    def play(
        self, bot, word_list_file: str = "words.txt", target_word: str = None
    ) -> None:
        def format_results(results) -> str:
            response = ""
            for letter in results:
                if letter.is_in_correct_place():
                    response = response + letter.letter
                elif letter.is_in_word():
                    response = response + "*"
                else:
                    response = response + "?"
            return response
        def set_feedback(guess: str, target_word: str) -> tuple[bool, list[Letter]]:
            correct: bool = True
            letters = []
            for j in range(len(guess)):
                letter = Letter(guess[j])
                if guess[j] == target_word[j]:
                    letter.in_correct_place = True
                    known_letters[j] = guess[j]  
                else:
                    correct = False
                if guess[j] in target_word:
                    letter.in_word = True
                else:
                    unused_letters.add(guess[j])  
                letters.append(letter)

            return correct, letters
        word_list: list(str) = list(
            map(lambda x: x.strip().upper(), open(word_list_file, "r").readlines())
        )
        known_letters: list(str) = [None, None, None, None, None]
        unused_letters = set()
        if target_word is None:
            target_word = random.choice(word_list).upper()
        else:
            target_word = target_word.upper()
            if target_word not in word_list:
                print(f"Target word {target_word} must be from the word list")
                self.err_input = True
                return
        print(
            f"Playing a game of WordyPy using the word list file of {word_list_file}.\nThe target word for this round is {target_word}\n"
        )
        MAX_GUESSES = 6
        for i in range(1, MAX_GUESSES):
            guess: str = bot.make_guess()
            print(f"Evaluating bot guess of {guess}")
            if guess not in word_list:
                print(f"Guessed word {guess} must be from the word list")
                self.err_guess = True
            elif guess in self.prev_guesses:
                print(f"Guess word cannot be the same one as previously used!")
                self.err_guess = True
            if self.err_guess:
                return
            self.prev_guesses.append(guess) 
            for j, letter in enumerate(guess):
                if letter in unused_letters:
                    print(
                        f"The bot's guess used {letter} which was previously identified as not used!"
                    )
                    self.err_guess = True
                if known_letters[j] is not None:
                    if letter != known_letters[j]:
                        print(
                            f"Previously identified {known_letters[j]} in the correct position is not used at position {j}!"
                        )
                        self.err_guess = True
                if self.err_guess:
                    return
            correct, results = set_feedback(guess, target_word)
            print(f"Was this guess correct? {correct}")

            print(f"Sending guess results to bot {format_results(results)}\n")

            bot.record_guess_results(guess, results)
            if correct:
                print(f"Great job, you found the target word in {i} guesses!")
                return
        print(
            f"Thanks for playing! You didn't find the target word in the number of guesses allowed."
        )
        return
if __name__ == "__main__":
    favorite_words = ["doggy", "drive", "daddy", "field", "state"]
    words_file = "temp_file.txt"
    with open(words_file, "w") as file:
        file.writelines("\n".join(favorite_words))
    bot = Bot(words_file)
    GameEngine().play(bot, word_list_file=words_file, target_word="doggy")