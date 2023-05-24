// (function() {


    var form = document.getElementById('book-search-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        var titleInput = document.getElementById('book_search_search_term');
        var title = titleInput.value;
        fetchBooks(title);
    });

    function fetchBooks(title) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/search/' + title, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var books = JSON.parse(xhr.responseText);
                displayBooks(books);
            }
        };
        xhr.send();
    }

    function displayBooks(books) {
        var booksContainer = document.getElementById('books');
        booksContainer.innerHTML = ''; // Clear existing content

        books.forEach(function(book) {

            var text = '<div id="books-container">'+
                '<div class="book">'+
                    '<img loading="lazy" src="'+book.book_cover+ '" alt="Entity Image">' +
                        '<div>'+
                            '<h2>'+ book.title + '</h2>'+
                            '<p> By ' + book.author + '</p>'+
                            '<p>Genre: ' + book.genres + '</p>'
                        +'</div>'
                // +'</div>'+
            +'</div>';

            booksContainer.innerHTML += text;
        });
    }





//})();