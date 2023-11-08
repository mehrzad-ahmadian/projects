! pip install tokenizers
! wget https://s3.amazonaws.com/research.metamind.io/wikitext/wikitext-103-raw-v1.zip
! unzip wikitext-103-raw-v1.zip

from tokenizers import Tokenizer
from tokenizers.models import WordPiece
from tokenizers.trainers import WordPieceTrainer
from tokenizers.pre_tokenizers import Whitespace

files = [f"wikitext-103-raw/wiki.{split}.raw" for split in ["test", "train", "valid"]]

tokenizer = Tokenizer(WordPiece(unk_token="[UNK]"))
trainer = WordPieceTrainer(special_tokens=["[UNK]", "[CLS]", "[SEP]", "[PAD]", "[MASK]"], vocab_size=3*10**6)
tokenizer.pre_tokenizer = Whitespace()
tokenizer.train(files, trainer)

print(tokenizer.get_vocab_size())

# exmaple for encoding
tokenized = tokenizer.encode("Python is dynamically typed and garbage-collected. It supports multiple programming paradigms, including structured (particularly procedural), object-oriented and functional programming. It is often described as a batteries included language due to its comprehensive standard library")
print("Tokens: ", len(tokenized.tokens), tokenized.tokens)