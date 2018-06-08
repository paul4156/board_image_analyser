# board_image_analyser
An image analyser for a board game

On a Mac computer (iMac or macbook), open terminal.

To open terminal, click find tool on the right top corner. Then type "terminal" to open it.

In terminal, run these one by one

```bash
/usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"

brew install php

brew install git

cd

git clone https://github.com/paul4156/board_image_analyser.git


```

Make sure the image(s) are jpeg files. Most cases are.

Put the image(s) in the same folder (home/board_image_analyser). Then back in terminal, run

```bash
cd

cd board_image_analyser

php analyse.php

```

Results are displayed on screen as well as saved in .txt files with the same names of the images. Say image.jpeg will have a image.txt holding the results.

In result, 1 = red, 2 = blue, 0 = nothing.

Enjoy!
paul.zhang4156@gmail.com
