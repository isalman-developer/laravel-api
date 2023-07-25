<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    </head>
    <body class="antialiased">
        <div class="row">
            <form action="{{ route('posts.store') }}" method="POST">
                @csrf
                <button class="btn btn-info">
                    Create Post
                </button>
            </form>
            <br>
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <button class="btn btn-info">
                    Create Comment
                </button>
            </form>
            <br>
            <form action="{{ route('users.destroy') }}" method="POST">
                @csrf @method('DELETE')
                <button class="btn btn-info">
                    Delete Relational User
                </button>
            </form>
        </div>
    </body>
</html>
