<div>
    @if(session()->has('message'))
    <div class="alert alert-info">{{ session('message') }}</div>
    @endif

    @if(session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session()->has('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Phone number input -->
    <div>
        <label for="phoneNumber">Phone Number</label>
        <input type="text" id="phoneNumber" wire:model="phoneNumber" placeholder="Enter your phone number">
        @error('phoneNumber') <span class="error">{{ $message }}</span> @enderror
    </div>

    <!-- Button to send verification code -->
    <button wire:click="sendVerificationCode">Send Verification Code</button>

    <!-- Code input for verification -->
    <div style="margin-top: 20px;">
        <label for="verificationCode">Verification Code</label>
        <input type="text" id="verificationCode" wire:model="verificationCode" placeholder="Enter the code you received">
        @error('verificationCode') <span class="error">{{ $message }}</span> @enderror
    </div>

    <!-- Button to verify the code -->
    <button wire:click="verifyCode">Verify Code</button>
</div>