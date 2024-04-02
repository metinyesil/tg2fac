    $(document).ready(function () {
      $('#loginForm').submit(function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
          type: 'POST',
          url: 'login.php',
          data: formData,
          dataType: 'json',
          success: function (response) {
            if (response.success) {
              $('#loginForm').hide();
              $('#otpContainer').show();
            } else {
              alert('Giriş başarısız. Lütfen bilgilerinizi kontrol edin.');
            }
          },
          error: function () {
            alert('Giriş başarısız. Lütfen bilgilerinizi kontrol edin.');
          }
        });
      });

      $('#otpForm').submit(function (e) {
        e.preventDefault();
        var otp = $('#otp').val();
        $.ajax({
          type: 'POST',
          url: 'login.php',
          data: { type: 'otp', otp: otp },
          dataType: 'json',
          success: function (response) {
            var resultMessage = response.success ? 'Doğru' : 'Yanlış';
            $('#otpResult').html('2 Faktör Kodu Doğrulama Sonucu: ' + resultMessage).show();
          },
          error: function () {
            alert('2 faktör kodu doğrulama sırasında bir hata oluştu. Lütfen tekrar deneyin.');
          }
        });
      });
    });
