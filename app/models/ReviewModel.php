<?php
function addReview($conn, $listing_id, $reviewer_id, $reviewee_id, $rating, $review_text) {
    $stmt = $conn->prepare("INSERT INTO reviews (listing_id, reviewer_id, reviewee_id, rating, review_text) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiis", $listing_id, $reviewer_id, $reviewee_id, $rating, $review_text);
    return $stmt->execute();
}

function getReviewsForUser($conn, $user_id) {
    $stmt = $conn->prepare("SELECT r.*, u.name as reviewer_name, l.title as listing_title 
                            FROM reviews r 
                            JOIN users u ON r.reviewer_id = u.id 
                            JOIN listings l ON r.listing_id = l.id 
                            WHERE r.reviewee_id = ? 
                            ORDER BY r.created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function canReview($conn, $listing_id, $reviewer_id, $reviewee_id) {
    // Check if auction ended and user is either buyer or seller
    $stmt = $conn->prepare("SELECT status, seller_id FROM listings WHERE id = ?");
    $stmt->bind_param("i", $listing_id);
    $stmt->execute();
    $listing = $stmt->get_result()->fetch_assoc();
    
    if ($listing['status'] != 'closed') return false;
    
    // Check if already reviewed
    $stmt2 = $conn->prepare("SELECT id FROM reviews WHERE listing_id = ? AND reviewer_id = ? AND reviewee_id = ?");
    $stmt2->bind_param("iii", $listing_id, $reviewer_id, $reviewee_id);
    $stmt2->execute();
    
    return $stmt2->get_result()->num_rows == 0;
}
?>