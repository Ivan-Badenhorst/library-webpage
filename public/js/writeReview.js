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
const user_id = 15
const score = document.getElementById('write_review_score')
const comment = document.getElementById('write_review_comment')

/**
 * event listener for write review event
 * @listens {Event} submit
 * @description fetches field values and calls writeReview
 */
writeReviewForm.addEventListener('submit', function(event) {
    event.preventDefault()
    let bookId = parseInt(book_id.textContent)
    let userId = user_id
    let reviewScore = parseInt(score.value)
    let reviewComment = comment.value

    writeReview(bookId, userId, reviewScore, reviewComment)
});

/**
 * Brief description of the function.
 *
 * @param {int} bookId - the id of the book being reviewed
 * @param {int} userId - the id of the user currently logged in
 * @param {int} reviewScore - the score of the review
 * @param {String} reviewComment - the comment of the review
 * @returns {void}
 */
function writeReview(bookId, userId, reviewScore, reviewComment){
    let xhr = new XMLHttpRequest();
    //open URL that returns JSON search results
    xhr.open('GET', '/write/' + bookId + '/' + userId + '/' + reviewScore + '/' + reviewComment, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log("got response")
        }
    };
    xhr.send();
}

