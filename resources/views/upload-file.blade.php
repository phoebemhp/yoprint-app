<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Upload File</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script>
            $(document).ready(function() {
                $('#uploadFile').on('change', function() {
                    if ($(this).val()) {
                        $('#uploadButton').prop('disabled', false);
                    } else {
                        $('#uploadButton').prop('disabled', true);
                    }
                });
            });
        </script>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center py-4 sm:pt-0 m-5">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('upload_file.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-10">
                                <input type="file" id="uploadFile" name="file" accept=".csv">
                            </div>
                            <div class="col-2">
                                <button class="btn btn-primary" id="uploadButton" type="submit">Upload File</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>File Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $file)
                                <tr>
                                    <td>
                                        {{ $file->created_at->toDateString() }} 
                                        {{ $file->created_at->format('h:i A') }} 
                                        <br/>
                                        {{ $file->created_at->diffForHumans() }}
                                    </td>
                                    <td>{{ $file->original_name }}</td>
                                    <td>{{ $file->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>