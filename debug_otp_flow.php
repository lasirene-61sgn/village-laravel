<?php
// Debug script to understand the OTP flow issue

echo "Customer Login/Password Reset Flow Analysis:\n\n";

echo "1. Regular Login Flow:\n";
echo "   - Customer requests OTP: sendOTP() -> sets customer.otp and customer.otp_expires_at\n";
echo "   - Customer uses OTP to login: verifyOTP() -> validates and clears OTP\n\n";

echo "2. Password Reset Flow:\n";
echo "   - Customer requests forgot password OTP: sendForgotPasswordOTP() -> sets customer.otp and customer.otp_expires_at\n";
echo "   - Customer uses OTP to reset password: resetPassword() -> validates and clears OTP\n\n";

echo "Potential Issues:\n";
echo "- Same OTP field used for both flows (customer.otp)\n";
echo "- If user requests OTP for login, then requests forgot password OTP, the first OTP is overwritten\n";
echo "- OTP expires after 10 minutes - if user takes longer to enter OTP, it will be expired\n";
echo "- No distinction between OTP for login vs OTP for password reset\n\n";

echo "Current Implementation:\n";
echo "- Both flows use the same customer.otp and customer.otp_expires_at fields\n";
echo "- OTPs are valid for 10 minutes from creation time\n";
echo "- After successful verification, OTP is cleared from the database\n\n";

echo "Recommendation:\n";
echo "The current implementation should work correctly as long as users enter the OTP within 10 minutes.\n";
echo "If users are getting 'expired OTP' errors, it might be because:\n";
echo "1. They requested an OTP but took more than 10 minutes to enter it\n";
echo "2. They had an old OTP in the system that expired\n";
echo "3. Timezone differences between server and client\n\n";

echo "Solution:\n";
echo "For better user experience, consider extending OTP validity to 15-30 minutes,\n";
echo "or implementing separate OTP fields for different purposes in the future.\n";