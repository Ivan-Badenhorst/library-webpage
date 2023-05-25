const addButton = document.getElementById('book-add-form')
const bookid = document.getElementById('bookId')
const favoriteButon = document.getElementById('book_add_add_to_favorites')

addButton.addEventListener('submit', function(event) {
    console.log(bookid.textContent)
    event.preventDefault()
    let bookId = parseInt(bookid.textContent)
    let userId = 15
    add(bookId, userId)

    if(favoriteButon.textContent === 'Add to favorites'){
        favoriteButon.textContent = 'Remove from favorites'
    }
    else{
        favoriteButon.textContent = 'Add to favorites'
    }
});


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