// Live bid updates for seller
function fetchBidUpdates() {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', '../../ajax/seller_bid_updates.php', true);
    
    xhr.onload = function() {
        if (this.status == 200) {
            let listings = JSON.parse(this.responseText);
            listings.forEach(listing => {
                let bidElement = document.getElementById(`high-${listing.id}`);
                let countElement = document.getElementById(`total-${listing.id}`);
                if (bidElement) {
                    bidElement.textContent = `$${listing.current_bid}`;
                }
                if (countElement) {
                    countElement.textContent = `${listing.bid_count} bids`;
                }
            });
        }
    };
    
    xhr.send();
}

// Poll every 5 seconds
if (document.getElementById('liveBids')) {
    fetchBidUpdates();
    setInterval(fetchBidUpdates, 5000);
}