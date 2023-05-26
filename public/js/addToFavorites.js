/**
 * @fileoverview JavaScript file for book page: to display book info and add/remove it from the reading list
 * @version 1.0.0
 */

/**
 * @author Aymeric Baume
 * @since 2023-05-25.
 */

const favoriteForm = document.getElementById('book-add-form')              //add/remove from favorites button
const bookid = document.getElementById('bookId')                           //field containing bookId
const userid = 15
const favoriteButton = document.getElementById('book_add_add_to_favorites') //actual button in the form

/**
 * event listener for add/remove from favorites submit event
 * @listens {Event} submit
 * @description calls add book and changes the state of the button depending on whether the userBook was or wasn't in the database
 */
favoriteForm.addEventListener('submit', function(event) {
    event.preventDefault()
    let bookId = parseInt(bookid.textContent)
    let userId = userid
    add(bookId, userId)

    if(favoriteButton.textContent === 'Add to favorites'){
        favoriteButton.textContent = 'Remove from favorites'
    }
    else{
        favoriteButton.textContent = 'Add to favorites'
    }
});

/**
 * Brief description of the function.
 *
 * @param {int} bookId - the id of the book being added/removed
 * @param {int} userId - the id of the user currently logged in
 * @returns {void}
 */
function add(bookId, userId){
    let xhr = new XMLHttpRequest();
    //open URL that returns JSON search results
    xhr.open('GET', '/add/' + bookId + '/' + userId, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log("got response")
        }
    };
    xhr.send();
}