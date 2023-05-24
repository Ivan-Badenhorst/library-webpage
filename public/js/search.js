//


    var searchBar = document.getElementById('book-search-form');
    var genreFilter = document.getElementById('filter_genre');
    var checkboxes = genreFilter.getElementsByTagName("input");
    var books;

(function() {
    searchBar.addEventListener('submit', function(event) {
        event.preventDefault();
        var titleInput = document.getElementById('book_search_search_term');
        var title = titleInput.value;
        fetchBooks(title);
    });

    genreFilter.addEventListener('change', function (event){

        var titleInput = document.getElementById('book_search_search_term');
        var title = "";
        title += titleInput.value;
        if( title === "" || title == null){
            title = "%"
        }
        fetchBooks(title);
        console.log(books);
    });





    function getGenres(){
        //get genres selected
        var selectedValues = [];

        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type === "checkbox" && checkboxes[i].checked) {
                selectedValues.push(checkboxes[i].value.toUpperCase());
            }
        }
        return selectedValues;
    }

    function fetchBooks(title) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/search/' + title, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                books = JSON.parse(xhr.responseText);
                displayBooks(getGenres());
            }
        };
        xhr.send();
    }

    function displayBooks(genres) {
        var booksContainer = document.getElementById('books');
        booksContainer.innerHTML = ''; // Clear existing content

        books.forEach(function(book) {
            if(checkStringForElements(book.genres.toUpperCase(), genres)) {
                var text = '<div id="books-container">' +
                    '<div class="book">' +
                    '<img loading="lazy" src="' + book.book_cover + '" alt="Entity Image">' +
                    '<div>' +
                    '<h2>' + book.title + '</h2>' +
                    '<p> By ' + book.author + '</p>' +
                    '<p>Genre: ' + book.genres + '</p>'
                    + '</div>'
                    // +'</div>'+
                    + '</div>';

                booksContainer.innerHTML += text;
            }


        });
    }



    function checkStringForElements(string, elements) {
        return elements.some(element => string.includes(element));
    }

})();