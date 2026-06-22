<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>ملیسان | نظرسنجی</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/style-change.css')}}">

</head>

<body>

    <div class="survey-container">
        <div class="survey-card">
            <div class="survey-header">
                <div class="survey-icon">
                    <i class="fas fa-poll"></i>
                </div>
                <h2 class="survey-title">دوست من ! این یه فرم نظرسنجیه</h2>
                <p class="survey-subtitle">لطفا با دقت جواب بده</p>
            </div>

            <div class="survey-body">
                <div class="survey-question">
                    <p class="question-text">من در کارهایم خیلی با برنامه نیستم</p>

                    <div class="options-grid">
                        <label class="option-item">
                            <input type="radio" name="survey" value="completely-disagree">
                            <span class="option-label">کاملاً مخالفم</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="survey" value="disagree">
                            <span class="option-label">مخالفم</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="survey" value="neutral">
                            <span class="option-label">نظری ندارم</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="survey" value="agree">
                            <span class="option-label">موافقم</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="survey" value="completely-agree">
                            <span class="option-label">کاملاً موافقم</span>
                        </label>
                    </div>
                </div>

                <div class="survey-actions">
                    <button type="submit" class="submit-btn">
                        <span>ارسال</span>
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>