// AJAX Search/Filter for Buyer
document.getElementById('searchBtn')?.addEventListener('click', function() {
    let search = document.getElementById('searchInput').value;
    let category = document.getElementById('categoryFilter').value;
    
    let xhr = new XMLHttpRequest();
    xhr.open('GET', '../../ajax/buyer_search.php?search=' + encodeURIComponent(search) + '&category=' + category, true);
    xhr.onload = function() {
        if (this.status == 200) {
            let listings = JSON.parse(this.responseText);
            let resultsDiv = document.getElementById('searchResults');
            
            if (listings.length === 0) {
                resultsDiv.innerHTML = '<p>No auctions found.</p>';
                return;
            }
            
            let html = '<div class="listings-grid">';
            listings.forEach(listing => {
                html += `
                    <div class="listing-card">
                        <h4>${listing.title}</h4>
                        <p>Current Bid: $${listing.current_bid}</p>
                        <p>Category: ${listing.category_name}</p>
                        <a href="details.php?id=${listing.id}" class="btn btn-sm">View Details</a>
                    </div>
                `;
            });
            html += '</div>';
            resultsDiv.innerHTML = html;
        }
    };
    xhr.send();
});

// AJAX Bid Submission
function placeBid(listingId, bidAmount) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '../../ajax/place_bid.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (this.status == 200) {
            let response = JSON.parse(this.responseText);
            if (response.success) {
                alert(response.message);
                location.reload();
            } else {
                alert(response.message);
            }
        }
    };
    
    xhr.send(`listing_id=${listingId}&bid_amount=${bidAmount}`);
}