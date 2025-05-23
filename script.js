function copyText(elementId) {
    const text = document.getElementById(elementId).innerText;
    navigator.clipboard.writeText(text)
        .then(() => {
            const notification = document.getElementById('copyNotification');
            notification.style.display = 'block';
            
            setTimeout(() => {
                notification.style.display = 'none';
            }, 2000);
            
            document.getElementById('email').value = text;
        })
        .catch(err => {
            console.error('Failed to copy text: ', err);
        });
}