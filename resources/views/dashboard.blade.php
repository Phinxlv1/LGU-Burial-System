<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — LGU Carmen</title>
    <style>
        body { font-family: sans-serif; background: #f7f3ee; 
               display:flex; align-items:center; justify-content:center; 
               min-height:100vh; margin:0; }
        .card { background: white; padding: 2rem 3rem; border-radius: 12px; 
                text-align:center; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        h1 { color: #0f1f3d; margin-bottom: 0.5rem; }
        p { color: #888; margin-bottom: 1.5rem; }
        form button { background: #0f1f3d; color: white; border: none; 
                      padding: 0.7rem 1.5rem; border-radius: 8px; 
                      cursor: pointer; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Welcome, {{ auth()->user()->name }}!</h1>
        <p>LGU Carmen — Burial Permit System</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>

