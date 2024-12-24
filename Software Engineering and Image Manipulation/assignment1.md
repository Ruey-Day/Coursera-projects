```python
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
```


```python
# This cell has the tests your Letter class should pass in order to
# be evaluated as correct. Some of the tests you can see here and
# try on your own (press the button labeled validate on the toolbar).
# Others are hidden from your view, and will be evaluated only when
# you submit to the autograder.

# Check if the Letter class exists
assert "Letter" in dir(), "The Letter class does not exist, did you define it?"

# Check to see if the Letter class can be created
l: Letter
try:
    l = Letter("s")
except:
    assert (
        False
    ), "Unable to create a Letter object with Letter('s'), did you correctly define the Letter class?"

# Check to see if the Letter class has the in_correct_place attribute
assert hasattr(
    l, "in_correct_place"
), "The letter object created has no in_correct_place attribute, but this should be False by default. Did you create this attribute?"

```


```python
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

```


```python
# Tests for Bot class.

# Check if the Bot class exists
assert "Bot" in dir(), "The Bot class does not exist, did you define it?"

```


```python
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

            self.prev_guesses.append(guess)  # record the previous guess

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
```


```python
import random


class GameEngine:
    def __init__(self):
        self.err_input = False
        self.err_guess = False
        self.prev_guesses = []  # record the previous guesses

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
                    known_letters[j] = guess[j]  # record the known correct positions
                else:
                    correct = False

                if guess[j] in target_word:
                    letter.in_word = True
                else:
                    unused_letters.add(guess[j])  # record the unused letters

                # add this letter to our list of letters
                letters.append(letter)

            return correct, letters

        # read in the dictionary of allowable words
        word_list: list(str) = list(
            map(lambda x: x.strip().upper(), open(word_list_file, "r").readlines())
        )
        # record the known correct positions
        known_letters: list(str) = [None, None, None, None, None]
        # set of unused letters
        unused_letters = set()

        # assign the target word to a member variable for use later
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
            # ask the bot for it's guess and evaluate
            guess: str = bot.make_guess()

            # print out a line indicating what the guess was
            print(f"Evaluating bot guess of {guess}")

            if guess not in word_list:
                print(f"Guessed word {guess} must be from the word list")
                self.err_guess = True
            elif guess in self.prev_guesses:
                print(f"Guess word cannot be the same one as previously used!")
                self.err_guess = True

            if self.err_guess:
                return

            self.prev_guesses.append(guess)  # record the previous guess

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

            # get the results of the guess
            correct, results = set_feedback(guess, target_word)

            # print out a line indicating whether the guess was correct or not
            print(f"Was this guess correct? {correct}")

            print(f"Sending guess results to bot {format_results(results)}\n")

            bot.record_guess_results(guess, results)

            # if they got it correct we can just end
            if correct:
                print(f"Great job, you found the target word in {i} guesses!")
                return

        # if we get here, the bot didn't guess the word
        print(
            f"Thanks for playing! You didn't find the target word in the number of guesses allowed."
        )
        return
```


```python
if __name__ == "__main__":
    # Chris's favorite words
    favorite_words = ["doggy", "drive", "daddy", "field", "state"]

    # Write this to a temporary file
    words_file = "temp_file.txt"
    with open(words_file, "w") as file:
        file.writelines("\n".join(favorite_words))

    # Initialize the student Bot
    bot = Bot(words_file)

    # Create a new GameEngine and play a game with the Bot, in this
    # test run I chose to set the target_word to "doggy"
    GameEngine().play(bot, word_list_file=words_file, target_word="doggy")
```

    Playing a game of WordyPy using the word list file of temp_file.txt.
    The target word for this round is DOGGY
    
    Evaluating bot guess of DADDY
    Was this guess correct? False
    Sending guess results to bot D?**Y
    
    Evaluating bot guess of DOGGY
    Was this guess correct? True
    Sending guess results to bot DOGGY
    
    Great job, you found the target word in 2 guesses!



```python

```
