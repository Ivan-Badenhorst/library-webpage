const reviewButton = document.getElementById('book-review-form');
const reviewDiv = document.getElementById('reviewDisplay');
var num = -5;




reviewButton.addEventListener('submit', function(event) {
    event.preventDefault();
    let bookId = document.getElementById('bookId').textContent;
    num += 5;
    console.log(num)
    getReviews(bookId, num);
});


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



function displayReviews(reviews) {

    let button = document.getElementById('book_review_view_reviews');
    button.innerText = "see more..."
    reviews.forEach(function(review) {
        //only display book if it contains at least one of the selected genres

        let text = '<div className="review">' +
            '<div className="rating">' +
            '<p>Score:' + review.score+ '</p>'+
            '<h3>'+ review.display_name+'</h3>'+
            '<b>'+review.date_added+'</b>'+
            '<p>' + review.comment + '</p>'+
            '</div>'+'</div>'

        reviewDiv.innerHTML = reviewDiv.innerHTML + text;


    });

}
