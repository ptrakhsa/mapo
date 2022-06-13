<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .shimmer {
            position: relative;
            background: #f6f7f8;
            background-image: linear-gradient(to right, #f6f7f8 0%, #f2f4f7 10%, #f0f0f2 20%, #f2f4f7 30%, #f6f7f8 40%, #f6f7f8 100%);
            background-repeat: no-repeat;
            background-size: 800px 200px;

            /* Animation */
            -webkit-animation-duration: 1s;

            /* Specifies style for element when animation isn't playing */
            -webkit-animation-fill-mode: forwards;

            -webkit-animation-iteration-count: infinite;
            -webkit-animation-name: shimmer;
            -webkit-animation-timing-function: ease-in-out;
        }

        @-webkit-keyframes shimmer {
            0% {
                background-position: -400px 0;
            }

            100% {
                background-position: 400px 0;
            }
        }

        #shimmer-container {
            width: 500px;
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #shimmer-square {
            width: 150px;
            height: 150px;
            border-radius: 20px;
        }

        #shimmer-content {
            flex: 1;
            height: 150px;
            width: 100%;
            padding: 0.5rem 1rem 0 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-end;
        }

        #shimmer-title {
            width: 100%;
            height: 30px;
            margin-bottom: 1rem;
            border-radius: 20px;
        }

        #shimmer-desc {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            align-items: flex-start;
        }

        .line {
            height: 10px;
            border-radius: 20px;
        }

        .line-width-100 {
            width: 100%;
        }

        .line-width-70 {
            width: 70%;
        }

        .line-width-50 {
            width: 50%;
        }

    </style>
</head>

<body>
    <div id="shimmer-container">
        <div id="shimmer-square" class="shimmer"></div>
        <div id="shimmer-content">
            <div id="shimmer-title" class="shimmer"></div>
            <div id="shimmer-desc">
                <div class="line shimmer line-width-100"></div>
                <div class="line shimmer line-width-100"></div>
                <div class="line shimmer line-width-70"></div>
                <div class="line shimmer line-width-50"></div>
            </div>
        </div>
    </div>

</body>

</html>
