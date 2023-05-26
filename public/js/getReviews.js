/**
 * @fileoverview JavaScript file to control review display of book-info page
 * @version 1.0.0
 */

/**
 * @author Ivan Badenhorst
 * @since 2023-05-25.
 */


const reviewButton = document.getElementById('book-review-form');
const reviewDiv = document.getElementById('reviewDisplay');
var num = -5;


/**
 * event listener for the view more reviews button
 * @listens {Event} submit
 * @description adds a new set of reviews to the display each time the button is clicked
 */
reviewButton.addEventListener('submit', function(event) {
    event.preventDefault();
    let bookId = document.getElementById('bookId').textContent;
    num += 5;
    console.log(num)
    getReviews(bookId, num);
});


/**
 * gets a list of reviews as json to display -> see BookInfoController for route
 *
 * @param {string} bookId -> id of the book for which you want reviews
 * @param {int} offset -> search offset ex: if =5, reviews returned will start at the 6th review (ordered by date/time)
 * @return void
 */
function getReviews(bookId, offset){
    let xhr = new XMLHttpRequest();
    //open URL that returns JSON search results
    xhr.open('GET', '/review/' + bookId +'/' + offset, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            let reviews = JSON.parse(xhr.responseText);
            console.log(reviews);
            displayReviews(reviews);
        }
    };
    xhr.send();
}


/**
 * Displays new reviews under the previously displayed reviews
 *
 * @param {JSON} reviews -> json containing reviews to display. json format:
 *                                      [{
 *                                          "comment":"",
 *                                          "score":0,
 *                                          "date_added":"",
 *                                          "display_name":""
 *                                      }, ...]
 * @return void
 * @description adds reviews into the 'reviewDisplay' div
 */
function displayReviews(reviews) {

    let button = document.getElementById('book_review_view_reviews');
    button.innerText = "see more..."
    reviews.forEach(function(review) {

        let ratingHTML = ''
        for (var i = 0; i < 5; i++) {
            if (i < (review.score/2)) {
                ratingHTML += '<span class="fa fa-star checked"></span>'; // Filled star symbol
            } else {
                ratingHTML += '<span class="fa fa-star"></span>'; // Empty star symbol
            }
        }

        let text = '<div class="review">' +
            '<h3>'+ review.display_name+'</h3>'+
            '<div class="rating">' +
                ratingHTML+
            '</div>'+
            '<b>'+review.date_added+'</b>'+
            '<p>' + review.comment + '</p>'+
            '</div>'

        reviewDiv.innerHTML = reviewDiv.innerHTML + text;


    });

}
