/**
 * @fileoverview JavaScript file  to update and display books
 * @version 1.1.0
 */

/**
 * @author Ivan Badenhorst
 * @since 2023-05-25.
 */



    const searchBar = document.getElementById('book-search-form');     //search bar on home page
    const genreFilter = document.getElementById('filter_genre');       //genres selector on home page
    const checkboxes = genreFilter.getElementsByTagName("input");   //list of checkboxes
    const nextButton = document.getElementById('nextButton');
    const previousButton = document.getElementById('previousButton')
    const currentPage = document.getElementById('currentPage')
    const titleInput = document.getElementById('book_search_search_term');

    let books;
    let page;//array used for storing books that are displayed





    document.addEventListener('DOMContentLoaded', function() {
        page = 0;
        console.log("page");
        currentPage.disabled = false;
        currentPage.value = page.toString();
        currentPage.disabled = true;
        previousButton.disabled = true;
    });




    nextButton.addEventListener('click', function(event) {
        event.preventDefault();
        let genres = getGenres();
        page += 1;
        currentPage.disabled = false;
        currentPage.value = page.toString();
        currentPage.disabled = true;
        previousButton.disabled = false;
        let title = "";
        title += titleInput.value;
        if( title === "" || title == null){
            title = "%"
        }
        fetchBooks(title, genres);
    });

    previousButton.addEventListener('click', function(event){
        event.preventDefault();
        let genres = getGenres();
        page -= 1;
        currentPage.disabled = false;
        currentPage.value = page.toString();
        currentPage.disabled = true;
        if(page === 0){
            previousButton.disabled = true;
        }
        let title = "";
        title += titleInput.value;
        if( title === "" || title == null){
            title = "%"
        }
        fetchBooks(title, genres);
    });



/**
     * event listener for search bar submit event
     * @listens {Event} submit
     * @description extracts the value entered in the search bar and calls fetchBooks() on this value
     */
    searchBar.addEventListener('submit', function(event) {
        event.preventDefault();
        page = 0;

        let title = titleInput.value;
        let genres = getGenres();
        fetchBooks(title, genres);
    });


    /**
     * event listener for the list of genres
     * @listens {Event} change
     * @description refreshes the displayed books based on the newly selected list of genres
     */
    genreFilter.addEventListener('change', function (){

        let titleInput = document.getElementById('book_search_search_term');
        let title = "";
        title += titleInput.value;
        if( title === "" || title == null){
            title = "%"
        }

        page = 0;
        currentPage.disabled = false;
        currentPage.value = page.toString();
        currentPage.disabled = true;
        previousButton.disabled = true;

        let genres = getGenres();
        fetchBooks(title, genres);
        console.log(books);
    });

    nextButton.addEventListener('click', function(){

        //display next set of books

    });




    /**
     * Gets a list of all selected Genres in the checkboxes given on the home-page
     *
     * @returns {Array.<String>} Array of strings describing the selected genres
     */
    function getGenres(){
        //array to store selected genres
        let genres = [];

        //to ensure that there always is a genre:
        genres.push('genre_as_placeholder');

        //loop over the checkboxes and include those that have been selected
        for (let i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type === "checkbox" && checkboxes[i].checked) {
                genres.push(checkboxes[i].value);
            }
        }
        return genres;
    }


    /**
     * Brief description of the function.
     *
     * @param {String} title - a search term for looking for books -> function searches by title
     * @param {Array<String>} genres - a list of genres to use as filter in search
     * @returns {void}: function call displayBooks() as final  + updates global parameter books with a list of books
     * as return on the search
     */
    function fetchBooks(title, genres) {

        let genresString = '';
        let xhr = new XMLHttpRequest();
        if (genres.length < 1){
            genresString = 'x';
        }else{
            genresString = genres.join(",")
        }

        let offset = page*40;

        //open URL that returns JSON search results
        xhr.open('GET', '/search/' + title + '/' + genresString +'/' + offset.toString(), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                books = JSON.parse(xhr.responseText);
                //display books on page
                displayBooks(getGenres());
            }
        };
        xhr.send();
    }



    /**
     * Displays the books in the global parameter books based on given genres
     *
     * @param {Array.<String>} genres - List of genres to be included in the display
     * @return {void}
     * @description no return - function generes html code and adds to a display for each book in the global
     * variable books, but only if this book had genres within the list of genres provided.
     */
    function displayBooks(genres) {
        let booksContainer = document.getElementById('books');
        booksContainer.innerHTML = ''; // Clear existing content
        let count = 0;

        books.forEach(function(book) {
            count += 1;
            console.log(count);
            //only display book if it contains at least one of the selected genres
            if(count == 41){
                nextButton.disabled = false;
            }
            else{

                nextButton.disabled = true;

                let slash = '/';
                let onclickString = `onclick="switchPage('`+ slash + `book-info` + slash + book.id + `')"`;

                //if(checkStringForElements(book.genres.toUpperCase(), genres)) {
                    let text = '<div id="book-container"' + onclickString + '>' +
                        '<img loading="eager" src="' + book.book_cover + '" alt="Entity Image">' +
                        '<div>' +
                        '<h2>' + book.title + '</h2>' +
                        '<p> By ' + book.author + '</p>' +
                        '<p>Genre: ' + book.genres + '</p>'
                        + '</div>';

                    booksContainer.innerHTML += text;
            }


        });
    }


    /**
     * Checks if any elements in a list can be found within a given String
     *
     * @param {String} string - A String within which you want to check if elements of the list are valid
     * @param {Array.<String>} elements - A list of strings
     * @returns {Boolean} indicates whether any element in the array is present in string
     */
    function checkStringForElements(string, elements) {
        return elements.some(element => string.includes(element));
    }
