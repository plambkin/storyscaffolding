<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Submissions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .section {
            margin-bottom: 40px;
        }
        .label {
            font-weight: bold;
        }
        @page {
            margin: 100px 25px;
        }
        header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            text-align: center;
            line-height: 1.5;
        }
        footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            text-align: center;
            line-height: 1.5;
        }
        footer .page:after {
            content: counter(page);
        }
    </style>
</head>
<body>
    <header>
        <h1>{{ $user_name }}'s Submissions </h1> <!-- Display the current date here -->
    </header>

    <footer>
        <div class="page">
            Page <span class="page-number"></span>
        </div>
    </footer>

    @foreach ($submissions as $submission)
        <div class="section">
            <p class="label">Date of Submission:</p>
            <p>{{ $submission->formatted_date }}</p> <!-- Display the formatted submission date here -->
        </div>
        <div class="section">
            <p class="label">Exercise Type:</p>
            <p>{{ $submission->exercise_type }}</p>
        </div>
        <div class="section">
            <p class="label">Question:</p>
            <p>{{ $submission->textarea1 }}</p>
        </div>
        <div class="section">
            <p class="label">Answer:</p>
            <p>{{ $submission->textarea2 }}</p>
        </div>
        <div class="section">
            <p class="label">Feedback:</p>
            <p>{{ $submission->textarea3 }}</p>
        </div>
        <div class="section">
            <p class="label">Grade:</p>
            <p>{{ $submission->grade }}</p>
        </div>

        <!-- Add a page break after each submission -->
        <div style="page-break-after: always;"></div>
    @endforeach

</body>
</html>
