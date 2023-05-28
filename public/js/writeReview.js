/**
 * @fileoverview JavaScript file for book page: to write and add a review to the database
 * @version 1.0.0
 */

/**
 * @author Aymeric Baume
 * @since 2023-05-26.
 */

const writeReviewForm = document.getElementById('write-review-form')              //add/remove from favorites button
const book_id = document.getElementById('bookId')                                  //field containing bookId
const score = document.getElementById('write_review_score')
const comment = document.getElementById('write_review_comment')
const postButton = document.getElementById('write_review_submit_review')

let commentTextArea = document.getElementById('write_review_comment');
let boolValue = commentTextArea.getAttribute('logged');
let logged = (boolValue === 'true');

console.log("this executes at open");
if(logged === false){
    commentTextArea.textContent = 'You need to log in in order to place reviews';
    commentTextArea.disabled = true;
    postButton.disabled = true;
}


/**
 * event listener for write review event
 * @listens {Event} submit
 * @description fetches field values and calls writeReview
 */
writeReviewForm.addEventListener('submit', function(event) {
    event.preventDefault()
    let bookId = parseInt(book_id.textContent)
    let reviewScore = parseInt(score.value)
    let reviewComment = comment.value
    reviewComment = reviewComment.replace(/\./g, "%*%*%");

    writeReview(bookId, reviewScore, reviewComment)

    score.value = 0
    comment.value = ''
});

/**
 * Brief description of the function.
 *
 * @param {int} bookId - the id of the book being reviewed
 * @param {int} reviewScore - the score of the review
 * @param {String} reviewComment - the comment of the review
 * @returns {void}
 */
function writeReview(bookId, reviewScore, reviewComment){
    let xhr = new XMLHttpRequest();
    //open URL that returns JSON search results
    xhr.open('GET', 'https://a22web32.studev.groept.be/public/index.php/write/' + bookId + '/' + reviewScore + '/' + reviewComment, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log("got response")
        }
    };
    xhr.send();
}

