<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TaskBuddy – Your Daily Task Manager</title>
    <link rel="icon" href="fav.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
    }
    
    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: 
        radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(120, 119, 198, 0.2) 0%, transparent 50%);
      pointer-events: none;
    }
    
    .container {
      position: relative;
      z-index: 1;
    }
    
    .main-content {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 24px;
      padding: 3rem 2rem;
      box-shadow: 
        0 8px 32px rgba(0, 0, 0, 0.1),
        0 0 0 1px rgba(255, 255, 255, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.3);
      animation: fadeInUp 0.8s ease-out;
      max-width: 600px;
      margin: 0 auto;
    }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      33% { transform: translateY(-10px) rotate(1deg); }
      66% { transform: translateY(-5px) rotate(-1deg); }
    }
    
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }
    
    .display-4 {
      font-weight: 700;
      background: linear-gradient(135deg, #667eea, #764ba2, #f093fb);
      background-size: 200% 200%;
      background-clip: text;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: gradientShift 3s ease-in-out infinite;
      margin-bottom: 1rem;
    }
    
    @keyframes gradientShift {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }
    
    .emoji {
      display: inline-block;
      animation: float 3s ease-in-out infinite;
      font-size: 1.2em;
      margin-left: 0.5rem;
    }
    
    .lead {
      color: #4a5568;
      font-weight: 400;
      line-height: 1.6;
      margin-bottom: 2rem;
      animation: fadeInUp 0.8s ease-out 0.2s both;
    }
    
    .btn-container {
      animation: fadeInUp 0.8s ease-out 0.4s both;
    }
    
    .btn {
      font-weight: 600;
      padding: 0.875rem 2rem;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      font-size: 1.1rem;
      border: none;
      text-transform: none;
    }
    
    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }
    
    .btn:hover::before {
      left: 100%;
    }
    
    .btn-primary {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
      background: linear-gradient(135deg, #5a6fd8, #6a3e91);
      color: white;
    }
    
    .btn-outline-primary {
      background: rgba(255, 255, 255, 0.9);
      color: #667eea;
      border: 2px solid #667eea;
      backdrop-filter: blur(10px);
    }
    
    .btn-outline-primary:hover {
      background: #667eea;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
      border-color: #667eea;
    }
    
    .btn-lg {
      padding: 1rem 2.5rem;
      font-size: 1.1rem;
    }
    
    .me-2 {
      margin-right: 1rem;
    }
    
    footer {
      position: relative;
      z-index: 1;
      animation: fadeInUp 0.8s ease-out 0.6s both;
    }
    
    footer hr {
      border: none;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      margin: 2rem auto;
      max-width: 200px;
    }
    
    footer p {
      color: rgba(255, 255, 255, 0.8);
      font-weight: 300;
      font-size: 0.9rem;
    }
    
    /* Floating particles effect */
    .particle {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      pointer-events: none;
      animation: floatParticle 6s linear infinite;
    }
    
    @keyframes floatParticle {
      0% {
        transform: translateY(100vh) rotate(0deg);
        opacity: 0;
      }
      10% {
        opacity: 1;
      }
      90% {
        opacity: 1;
      }
      100% {
        transform: translateY(-100px) rotate(360deg);
        opacity: 0;
      }
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
      .main-content {
        margin: 2rem 1rem;
        padding: 2rem 1.5rem;
      }
      
      .display-4 {
        font-size: 2rem;
      }
      
      .btn-lg {
        padding: 0.875rem 2rem;
        font-size: 1rem;
        display: block;
        width: 100%;
        margin-bottom: 1rem;
      }
      
      .me-2 {
        margin-right: 0;
      }
    }
    
    /* Hover effects for the main container */
    .main-content:hover {
      transform: translateY(-5px);
      box-shadow: 
        0 12px 40px rgba(0, 0, 0, 0.15),
        0 0 0 1px rgba(255, 255, 255, 0.3);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
  </style>
</head>
<body class="bg-light">

  <!-- Floating particles -->
  <div class="particle" style="left: 10%; width: 4px; height: 4px; animation-delay: 0s;"></div>
  <div class="particle" style="left: 20%; width: 6px; height: 6px; animation-delay: 1s;"></div>
  <div class="particle" style="left: 30%; width: 3px; height: 3px; animation-delay: 2s;"></div>
  <div class="particle" style="left: 40%; width: 5px; height: 5px; animation-delay: 3s;"></div>
  <div class="particle" style="left: 50%; width: 4px; height: 4px; animation-delay: 4s;"></div>
  <div class="particle" style="left: 60%; width: 6px; height: 6px; animation-delay: 5s;"></div>
  <div class="particle" style="left: 70%; width: 3px; height: 3px; animation-delay: 0.5s;"></div>
  <div class="particle" style="left: 80%; width: 5px; height: 5px; animation-delay: 1.5s;"></div>
  <div class="particle" style="left: 90%; width: 4px; height: 4px; animation-delay: 2.5s;"></div>

  <div class="container text-center mt-5">
    <div class="main-content">
      <h1 class="display-4">Welcome to <span class="text-primary">TaskBuddy</span><span class="emoji">📝</span></h1>
      <p class="lead mt-3">Manage your daily tasks with ease and never miss a deadline again.</p>

      <div class="mt-4 btn-container">
        <a href="login.php" class="btn btn-primary btn-lg me-2">Login</a>
        <a href="register.php" class="btn btn-outline-primary btn-lg">Register</a>
      </div>
    </div>
  </div>

  <footer class="text-center mt-5 text-muted">
    <hr>
    <p>&copy; <?= date("Y") ?> TaskBuddy. All rights reserved.</p>
  </footer>

</body>
</html>