import os
import re
from llama_cpp import Llama
from llama_cpp.llama_types import *
from llama_cpp.llama_grammar import *

model: Llama = Llama(
    model_path=os.environ["LLAMA_13B"], verbose=False, n_ctx=2048
)
prompt= '''
Generate twenty human names like
Jun Zhu
Chendi De
Shan De
Meimei Han-han
Xiaobei Xin-xin
'''

grammar= r'''
root ::= (name "\n")+
sentence ::= [A-Z][A-Za-z]* 
firstname ::= sentence
lastname ::= sentence | sentence "-" sentence
name ::= firstname " " lastname
'''

def generate_names() -> list[str]:
    results: list[str] = []
    result = model.create_completion(prompt,
    grammar=LlamaGrammar.from_string(grammar=grammar), 
    stream=True, 
    max_tokens= 80)
    Final_text= ""
    for item in result:
        Final_text += item['choices'][0]['text']
        print(item['choices'][0]['text'], end="")

    lines = Final_text.split("\n")
    top_ten_lines = lines[:10]
    for line in top_ten_lines:
        results.append(line)
    return results
from contextlib import redirect_stderr
import tempfile

with redirect_stderr( tempfile.TemporaryFile('wt') ) as error_catcher:
    results = generate_names()

assert (
    len(results) == 10
), f"You did not return ten and only ten results, instead we got {len(results)}."

grammar = r'''
root ::= "1. " (sentence "\n") "2. " (sentence "\n") "3. " (sentence "\n") "4. " (sentence "\n") "5. " (sentence "\n")
sentence ::= [A-Z] [A-Za-z0-9 ,-]* ("." | "!" | "?")
'''
prompt = (
        "Generate a list of 5 things to do in Taiwan, each item be no more than three sentences long."
    )

def generate_trip_recommendations() -> list[str]:
    results: list[str] = []
    result = model.create_completion(prompt,
    grammar=LlamaGrammar.from_string(grammar=grammar), 
    stream=True, 
    max_tokens= 200)
    Final_text= ""
    for item in result:
        Final_text += item['choices'][0]['text']
        print(item['choices'][0]['text'], end="")
    lines = Final_text.split("\n")
    top_ten_lines = lines[:5]
    for line in top_ten_lines:
        results.append(line)
    return results

from contextlib import redirect_stderr
import re
import tempfile

with redirect_stderr( tempfile.TemporaryFile('wt') ) as error_catcher:
    results=generate_trip_recommendations()

# Verify length
assert (
    len(results) == 5
), f"You did not return five and only five results, instead we got {len(results)}."

from dataclasses import dataclass

@dataclass
class MailingAddress:
    name: str  # Full name, e.g. Dr. Christopher Brooks
    street_number: int  # Numeric address value, e.g. 105
    street_text: str  # Street information other than numeric address, e.g. S. State St.
    city: str  # City name, e.g. Ann Arbor
    state: str  # State name, only two letters, e.g. MI for Michigan
    zip_code_short: str  # The first five digits of the zip code, e.g. 48109, as a string value, since it could start with 0
    
grammar = r'''
root ::= (name "\n") (streetNumber "\n") (streetText "\n") (city "\n") (state "\n") (zip "\n")
name ::= [A-Z][A-Za-z]* " "[A-Z][A-Za-z]*
streetNumber ::= [0-9] [0-9] [0-9]
streetText ::= [A-Z][A-Za-z]* " St."
city ::= [A-Z][A-Za-z]*
state ::= [A-Z][A-Z]
zip ::= [0-9] [0-9] [0-9] [0-9] [0-9]
'''
prompt = "Generate 5 addresses like Christopher Brooks 105 State MI 48109"

def generate_addresses() -> list[MailingAddress]:
    results: list[MailingAddress] = []
    for i in range(5):
        result = model.create_completion(prompt, grammar=LlamaGrammar.from_string(grammar=grammar),
        stream=True, 
        max_tokens= 200)
        Final_text= ""
        for item in result:
            Final_text += item['choices'][0]['text']
            print(item['choices'][0]['text'], end="")
        lines = Final_text.split("\n")

        Temp = MailingAddress(
            name= lines[0],
            street_number= int(lines[1]),
            street_text= lines[2],
            city= lines[3],
            state= lines[4],
            zip_code_short= lines[5]
        )
        results.append(Temp)
    return results

from contextlib import redirect_stderr
import tempfile

with redirect_stderr( tempfile.TemporaryFile('wt') ) as error_catcher:
    results=generate_addresses()

assert (
    len(results) == 5
), f"You did not return five and only five results, instead we got {len(results)}."