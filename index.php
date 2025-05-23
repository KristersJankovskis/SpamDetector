<?php
$weights = [
    'free' => 0.8,
    'win' => 0.9,
    'offer' => 0.7,
    'click' => 0.2,
    'buy' => 0.5,
    'money' => 0.8,
    'lottery' => 0.9,
    'prize' => 0.9,
    'urgent' => 0.4,
    'credit' => 0.3
];
$threshold = 1.5;

function classifyEmail($email, $weights, $threshold) {
    $score = 0;
    $words = explode(" ", strtolower($email));
    
    foreach ($words as $word) {
        if (isset($weights[$word])) {
            $score += $weights[$word];
        }
    }

    return $score;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $score = classifyEmail($email, $weights, $threshold
);

    if($score > $threshold){
        $result = 'Spam';
        $class = 'spam';
    } else{
        $result = 'Not Spam';
        $class = 'not-spam';
    };
}

$nonSpamExample = "Hi John, I wanted to confirm our meeting tomorrow at 2pm to discuss the project timeline. Let me know if that still works for you. Thanks, Jane";
$spamExample = "URGENT: You won a free prize! Click here to claim your lottery money offer. Buy now with credit to win more!";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Spam Classifier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="copy-success" id="copyNotification">Copied to clipboard!</div>
    
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="example-box non-spam-box" onclick="copyText('nonSpamExample')">
                    <div class="example-title">
                        <i class="fas fa-check-circle me-1"></i> Non-Spam Text
                    </div>
                    <div class="example-content" id="nonSpamExample">
                        <?php echo htmlspecialchars($nonSpamExample); ?>
                    </div>
                    <div class="copy-instruction">
                        <i class="fas fa-copy me-1"></i> Click to copy
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="example-box spam-box" onclick="copyText('spamExample')">
                    <div class="example-title">
                        <i class="fas fa-exclamation-triangle me-1"></i> Spam Text
                    </div>
                    <div class="example-content" id="spamExample">
                        <?php echo htmlspecialchars($spamExample); ?>
                    </div>
                    <div class="copy-instruction">
                        <i class="fas fa-copy me-1"></i> Click to copy
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card classifier-card">
                    <div class="card-header text-center">
                        <i class="fas fa-shield-alt scanner-icon"></i>
                        <h2 class="mb-0">Email Spam Classifier</h2>
                        <p class="mb-0 opacity-75">Check if an email is likely to be spam</p>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Content</label>
                                <textarea class="form-control" name="email" id="email" placeholder="Paste your email content here..." required></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-classify">
                                    <i class="fas fa-search me-2"></i>Analyse Content
                                </button>
                            </div>
                        </form>
                        
                        <?php if (isset($result)): ?>
                            <div class="result-container">
                                <div class="result-badge <?php echo $class; ?>">
                                    <?php if ($class == 'spam'): ?>
                                        <i class="fas fa-exclamation-triangle"></i>
                                    <?php else: ?>
                                        <i class="fas fa-check-circle"></i>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($result); ?>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-muted mb-2 small">Spam signals detected:</p>
                                <div>
                                    <?php 
                                    $words = explode(" ", strtolower($_POST['email']));
                                    $found = false;
                                    foreach ($words as $word) {
                                        if (isset($weights[$word])) {
                                            echo '<span class="feature-item">' . htmlspecialchars($word) . '</span>';
                                            $found = true;
                                        }
                                    }
                                    if (!$found) {
                                        echo '<span class="text-muted">None detected</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>