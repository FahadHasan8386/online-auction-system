// General helper functions
function confirmAction(message) {
    return confirm(message);
}

// Auto-hide alerts after 3 seconds
document.addEventListener('DOMContentLoaded', function() {
    let alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 3000);
    });
});