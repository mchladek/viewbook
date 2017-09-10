Viewbook
========

Viewbook is a small, simple framework for displaying HTML versions of books. It
draws in book, chapter, and file details from a MongoDB collection, constructs
a simple table of contents and navigation, and displays the HTML contents of
the chapter requested.

Quick Guide
-----------

To use viewbook, do the following:

- Clone the repo
- Create a mongo database and collection
- Edit the `config_example.php` file, inputting the details of your domain and
  mongo database details and save as `config.php`
- Upload your HTML source directories to your viewbook base directory
- Edit your `.htaccess` file to properly resolve URLs

Details
-------

You will need to create a [MongoDB](https://www.mongodb.com/) database and
collection that stores the details of the books. The structure of the
collection follows the `example_book.json` file. I generally create a separate
JSON file for each book and import into the MongoDB collection. The `titleID`
of the book is the name of the folder in which the HTML files for each chapter
are located. The `file` field is the name of the HTML file without the `.html`
extension appended to it. So, based on the included `example_book.json` file,
there would be the following folder located within the base folder of viewbook:

```
book-url/
  ├── 01_ch00.html
  ├── 02_ch01.html
```

The other thing you need to do is edit the `config_example.php` file. After
making appropriate changes, the file should be renamed to `config.php`.

The navigation elements use the [Bootstrap](http://getbootstrap.com/)
framework, which is included.

The `css/` folder includes a responsive stylesheet that displays the chapter's
HTML appropriately for desktops, laptops, tablets, and phones. It also includes
a print stylesheet for printing the chapter.

Copyright
---------

See the LICENSE and COPYING files for details.
