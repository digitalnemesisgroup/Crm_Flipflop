<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo e(config('app.name', 'CRM')); ?></title>
<link rel="icon" type="image/png" href="<?php echo e(asset('bglogo.png')); ?>">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
<style>
  :root{
    --ink:#1E293B;
    --ink-soft:#475569;
    --paper:#FFFFFF;
    --paper-dim:#F1F5F9;
    --line:#E2E8F0;
    --accent:#3B82F6;
    --accent-dark:#2563EB;
    --accent-tint:#DBEAFE;
    --error:#EF4444;
    --radius:12px;
  }

  *{box-sizing:border-box;}
  html,body{margin:0;padding:0;}

  .guest-layout-wrapper{
    min-height:100vh;
    background: linear-gradient(-45deg, #1e3a8a, #3b82f6, #0284c7, #2563eb);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:32px 20px;
    font-family:'Inter', sans-serif;
    color:var(--ink);
    position: relative;
    overflow: hidden;
  }

  @keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  .guest-layout-wrapper::before,
  .guest-layout-wrapper::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    z-index: 0;
    pointer-events: none;
  }
  .guest-layout-wrapper::before {
    top: -100px;
    left: -100px;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
    animation: float 20s infinite linear;
  }
  .guest-layout-wrapper::after {
    bottom: -150px;
    right: -100px;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(14,165,233,0.3) 0%, rgba(255,255,255,0) 70%);
    animation: float 25s infinite linear reverse;
  }

  @keyframes float {
    0% { transform: rotate(0deg) translate(30px) rotate(0deg); }
    100% { transform: rotate(360deg) translate(30px) rotate(-360deg); }
  }

  .shell{
    width:100%;
    max-width:420px;
    z-index: 10;
    position: relative;
  }

  .brand{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:34px;
    justify-content:center;
  }
  .brand-mark{
    width:26px;
    height:26px;
    border-radius:7px;
    background: url('<?php echo e(asset('bglogo.png')); ?>') center/cover;
    flex-shrink:0;
  }
  .brand-name{
    font-family:'Fraunces', serif;
    font-size:24px;
    font-weight:600;
    letter-spacing:0.01em;
    color:#FFFFFF;
  }

  .card{
    background:var(--paper);
    border-radius:16px;
    padding:40px 36px 32px;
    box-shadow:
      0 1px 0 rgba(255,255,255,0.8) inset,
      0 12px 40px -12px rgba(0,0,0,0.15);
    border:1px solid rgba(0,0,0,0.05);
  }

  h1{
    font-family:'Fraunces', serif;
    font-weight:500;
    font-size:28px;
    line-height:1.2;
    margin:0 0 6px;
    color:var(--ink);
  }
  .sub{
    margin:0 0 26px;
    font-size:14.5px;
    color:var(--ink-soft);
    line-height:1.5;
  }

  /* Tabs */
  .tabs{
    display:flex;
    gap:22px;
    border-bottom:1px solid var(--line);
    margin-bottom:24px;
  }
  .tab{
    appearance:none;
    background:none;
    border:none;
    padding:0 0 12px;
    font-family:'Inter', sans-serif;
    font-size:14.5px;
    font-weight:500;
    color:var(--ink-soft);
    cursor:pointer;
    position:relative;
    letter-spacing:0.01em;
  }
  .tab::after{
    content:"";
    position:absolute;
    left:0; right:0; bottom:-1px;
    height:2px;
    background:var(--accent);
    transform:scaleX(0);
    transform-origin:left;
    transition:transform .22s ease;
  }
  .tab[aria-selected="true"]{
    color:var(--ink);
  }
  .tab[aria-selected="true"]::after{
    transform:scaleX(1);
  }
  .tab:focus-visible{
    outline:2px solid var(--accent);
    outline-offset:3px;
    border-radius:3px;
  }

  /* Forms */
  .panel{ display:none; }
  .panel.active{ display:block; animation:fade .28s ease; }
  @keyframes fade{
    from{ opacity:0; transform:translateY(4px); }
    to{ opacity:1; transform:translateY(0); }
  }

  .field{
    margin-bottom:16px;
  }
  .field label{
    display:block;
    font-size:13px;
    font-weight:500;
    color:var(--ink);
    margin-bottom:7px;
  }
  .input-wrap{
    position:relative;
  }
  input[type="email"],
  input[type="password"],
  input[type="text"]{
    width:100%;
    padding:12px 14px;
    font-size:14.5px;
    font-family:'Inter', sans-serif;
    border:1px solid var(--line);
    border-radius:var(--radius);
    background:#FFFFFF;
    color:var(--ink);
    transition:border-color .15s ease, box-shadow .15s ease;
  }
  input::placeholder{ color:#A6A091; }
  input:focus{
    outline:none;
    border-color:var(--accent);
    box-shadow:0 0 0 3px var(--accent-tint);
  }

  .pw-toggle{
    position:absolute;
    right:12px; top:50%;
    transform:translateY(-50%);
    background:none;
    border:none;
    font-size:12.5px;
    font-weight:500;
    color:var(--ink-soft);
    cursor:pointer;
    padding:4px;
  }
  .pw-toggle:hover{ color:var(--ink); }

  .row-between{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin:2px 0 22px;
    font-size:13.5px;
  }
  .remember{
    display:flex;
    align-items:center;
    gap:8px;
    color:var(--ink-soft);
  }
  .remember input{
    width:15px; height:15px;
    accent-color:var(--accent);
  }
  .link{
    color:var(--accent-dark);
    text-decoration:none;
    font-weight:500;
  }
  .link:hover{ text-decoration:underline; }

  button.primary{
    width:100%;
    padding:12.5px;
    border:none;
    border-radius:var(--radius);
    background:var(--ink);
    color:#F6F3EC;
    font-family:'Inter', sans-serif;
    font-size:14.5px;
    font-weight:600;
    cursor:pointer;
    transition:background .15s ease, transform .05s ease;
  }
  button.primary:hover{ background:#262C39; }
  button.primary:active{ transform:translateY(1px); }
  button.primary:disabled{
    background:#C9C4B6;
    cursor:not-allowed;
  }

  .hint{
    font-size:12.5px;
    color:var(--ink-soft);
    margin-top:10px;
    line-height:1.5;
  }

  /* OTP */
  .otp-sent-to{
    font-size:13.5px;
    color:var(--ink-soft);
    margin-bottom:18px;
    line-height:1.5;
  }
  .otp-sent-to strong{ color:var(--ink); font-weight:600; }
  .otp-boxes{
    display:flex;
    gap:8px;
    margin-bottom:18px;
  }
  .otp-boxes input{
    width:100%;
    text-align:center;
    padding:12px 0;
    font-size:18px;
    font-weight:600;
    letter-spacing:0;
  }

  .resend-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-size:13px;
    margin-bottom:20px;
    color:var(--ink-soft);
  }
  .resend-row button{
    background:none;
    border:none;
    color:var(--accent-dark);
    font-weight:500;
    font-size:13px;
    cursor:pointer;
    padding:0;
  }
  .resend-row button:disabled{
    color:#B8B2A2;
    cursor:not-allowed;
  }

  .change-email{
    background:none;
    border:none;
    font-size:13px;
    color:var(--ink-soft);
    text-decoration:underline;
    cursor:pointer;
    padding:0;
    margin-top:14px;
  }

  .divider{
    display:flex;
    align-items:center;
    gap:12px;
    margin:26px 0 20px;
  }
  .divider::before, .divider::after{
    content:"";
    flex:1;
    height:1px;
    background:var(--line);
  }
  .divider span{
    font-size:12.5px;
    color:var(--ink-soft);
  }

  .oauth-row{
    display:flex;
    gap:10px;
  }
  .oauth-btn{
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    padding:10px;
    border:1px solid var(--line);
    border-radius:var(--radius);
    background:#FFFFFF;
    font-size:13.5px;
    font-weight:500;
    color:var(--ink);
    cursor:pointer;
    transition:background .15s ease;
  }
  .oauth-btn:hover{ background:var(--paper-dim); }

  .signup{
    text-align:center;
    margin-top:26px;
    font-size:13.5px;
    color:var(--ink-soft);
  }

  .status-line{
    display:flex;
    align-items:center;
    gap:7px;
    font-size:13px;
    color:var(--accent-dark);
    margin:-6px 0 18px;
    min-height:16px;
  }
  .status-dot{
    width:6px; height:6px;
    border-radius:50%;
    background:var(--accent);
    flex-shrink:0;
  }

  @media (prefers-reduced-motion: reduce){
    .panel.active{ animation:none; }
  }
</style>
</head>
<body>
<div class="guest-layout-wrapper">
    <div class="shell">
      <div class="brand">
        <div class="brand-mark"></div>
        <div class="brand-name">CRM</div>
      </div>

      <div class="card">
          <?php echo e($slot); ?>

      </div>
    </div>
</div>
</body>
</html><?php /**PATH /home/ubuntu/Desktop/flipflop/payout-system/resources/views/layouts/guest.blade.php ENDPATH**/ ?>