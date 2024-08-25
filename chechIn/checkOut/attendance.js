document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('send-otp').addEventListener('click', sendOTP);
    document.getElementById('verify-otp').addEventListener('click', verifyOTP);
});

async function sendOTP() {
    const email = document.getElementById('email').value;
    console.log(`Sending OTP to ${email}`);

    try {
        const response = await fetch('./sendOtp.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email })
        });

        console.log('Response received from server.');

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const text = await response.text();
        console.log('Response text:', text);

        const result = JSON.parse(text); // Parse after logging
        console.log('Result:', result);

        if (result.success) {
            document.getElementById('otpSection').style.display = 'block';
            alert('OTP sent to your email.');
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error('Error during sendOTP:', error);
        alert('An error occurred while sending the OTP. Please try again.');
    }
}

async function verifyOTP() {
    const email = document.getElementById('email').value;
    const otp = document.getElementById('otp').value;
    console.log(`Verifying OTP for ${email} with OTP ${otp}`);

    try {
        const response = await fetch('./verifyOtp.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email, otp: otp })
        });

        console.log('Response received from server.');

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const text = await response.text();
        console.log('Response text:', text);

        const result = JSON.parse(text); // Parse after logging
        console.log('Result:', result);

        if (result.success) {
            alert(`You checked in at: ${result.dateTime}`);
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error('Error during verifyOTP:', error);
        alert('An error occurred while verifying the OTP. Please try again.');
    }
}
