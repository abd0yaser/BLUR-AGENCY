function sendMessage() {
    var name = document.getElementById('name').value.trim();
    var email = document.getElementById('email').value.trim();
    var message = document.getElementById('message').value.trim();

    if (name === '' || email === '' || message === '') {
        alert('Please enter your name, email, and message.');
        return;
    }

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        return;
    }

    var data = {
        "Name": name,
        "Email": email,
        "Message": message
    };

    var sendButton = document.getElementById('sendButton');
    sendButton.disabled = true;

    var spinner = document.getElementById('spinner');
    spinner.style.display = 'block';

    fetch('https://Gv12.edumateapp.com/api/Teleware/SendEmail', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            spinner.style.display = 'none';
            sendButton.disabled = false;
            if (response.ok) {
                alert('Email sent successfully');
            } else {
                alert('Failed to send email');
            }
        })
        .catch(error => {
            spinner.style.display = 'none';
            sendButton.disabled = false;
            console.error('Error:', error);
            alert('An error occurred while sending email');
        });
}

document.getElementById('sendButton').addEventListener('click', sendMessage);
