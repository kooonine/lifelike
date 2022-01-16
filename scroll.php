<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

</head>

<style>
    * {
        box-sizing: border-box;
        padding: 0;
        margin: 0;
    }

    #wrap {
        position: fixed;
        display: block;
        width: 100%;
        height: 100%;
    }

    .wrap_section {
        display: block;
        height: 100%;
        width: 100%;
        border: 1px solid #000000;
    }
</style>

<body>
    <div id="wrap">
        <section class="wrap_section">1</section>
        <section class="wrap_section">2</section>
        <section class="wrap_section">3</section>
        <section class="wrap_section">4</section>
        <section class="wrap_section">5</section>
    </div>

    <script>
        console.log($.event.special.swipe);
        $("body").swipe(function (e) {
            console.log(e);
        });
    </script>
</body>

</html>