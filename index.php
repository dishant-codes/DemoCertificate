<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form</title>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600);

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
            -moz-font-smoothing: antialiased;
            -o-font-smoothing: antialiased;
            font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        body {
            font-family: "Open Sans", Helvetica, Arial, sans-serif;
            font-weight: 300;
            font-size: 12px;
            line-height: 30px;
            color: #777;
            background: #0CF;
        }

        .container {
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
            position: relative;
        }

        #contact input[type="text"], #contact input[type="email"], #contact input[type="tel"], #contact input[type="url"], #contact textarea, #contact button[type="submit"] {
            font: 400 12px/16px "Open Sans", Helvetica, Arial, sans-serif;
        }

        #contact {
            background: #F9F9F9;
            padding: 25px;
            margin: 50px 0;
        }

        #contact h3 {
            color: #F96;
            display: block;
            font-size: 30px;
            font-weight: 400;
        }

        #contact h4 {
            margin: 5px 0 15px;
            display: block;
            font-size: 13px;
        }

        fieldset {
            border: medium none !important;
            margin: 0 0 10px;
            min-width: 100%;
            padding: 0;
            width: 100%;
        }

        #contact input[type="text"], #contact input[type="email"], #contact input[type="tel"], #contact input[type="url"], #contact textarea {
            width: 100%;
            border: 1px solid #CCC;
            background: #FFF;
            margin: 0 0 5px;
            padding: 10px;
        }

        #contact input[type="text"]:hover, #contact input[type="email"]:hover, #contact input[type="tel"]:hover, #contact input[type="url"]:hover, #contact textarea:hover {
            -webkit-transition: border-color 0.3s ease-in-out;
            -moz-transition: border-color 0.3s ease-in-out;
            transition: border-color 0.3s ease-in-out;
            border: 1px solid #AAA;
        }

        #contact textarea {
            height: 100px;
            max-width: 100%;
            resize: none;
        }

        #contact button[type="submit"] {
            cursor: pointer;
            width: 100%;
            border: none;
            background: #0CF;
            color: #FFF;
            margin: 0 0 5px;
            padding: 10px;
            font-size: 15px;
        }

        #contact button[type="submit"]:hover {
            background: #09C;
            -webkit-transition: background 0.3s ease-in-out;
            -moz-transition: background 0.3s ease-in-out;
            transition: background-color 0.3s ease-in-out;
        }

        #contact button[type="submit"]:active {
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.5);
        }

        #contact input:focus, #contact textarea:focus {
            outline: 0;
            border: 1px solid #999;
        }

        ::-webkit-input-placeholder {
            color: #888;
        }

        :-moz-placeholder {
            color: #888;
        }

        ::-moz-placeholder {
            color: #888;
        }

        :-ms-input-placeholder {
            color: #888;
        }
    </style>
    <script
            src="https://code.jquery.com/jquery-3.4.1.js"
            integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
            crossorigin="anonymous"></script>
    <!-- jsPDF library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    <script>


        let userCount;

        fetch("https://api.apispreadsheets.com/data/y8nOWQkyc9OC8Hah/?count").then(res=>{
            if (res.status === 200){
                // SUCCESS
                res.json().then(data=>{
                    const yourData = data
                    userCount = yourData.count;
                    console.log(userCount)
                }).catch(err => console.log(err))
            }
            else{
                // ERROR
            }
        })


        function myForm(e) {
            e.preventDefault();
            var doc = new jsPDF();
            var specialElementHandlers = {
                '#elementH': function (element, renderer) {
                    return true;
                }
            };
            let name = $("#name").val();
            let email = $("#email").val();
            let userID = userCount+1;
            doc.fromHTML("<div>Id "+userID+"<br>Name "+name+" <br>Email "+email+"</div>", 15, 15, {
                'width': 170,
                'elementHandlers': specialElementHandlers
            });


            fetch("https://api.apispreadsheets.com/data/y8nOWQkyc9OC8Hah/", {
                method: "POST",
                body: JSON.stringify({"data": {"Timestamp": showTime(), "Name": name, "Email": email, "ID": userID}}),
            }).then(res => {
                if (res.status === 201) {
                    // SUCCESS
                    console.log("sent");
                    doc.save('sample-document.pdf');
                } else {
                    // ERROR
                    console.log("error");
                }
            })
        // });
        }

        function showTime() {
            var date = new Date();
            var h = date.getHours(); // 0 - 23
            var m = date.getMinutes(); // 0 - 59
            var s = date.getSeconds(); // 0 - 59
            var session = "AM";

            if (h == 0) {
                h = 12;
            }

            if (h > 12) {
                h = h - 12;
                session = "PM";
            }

            h = (h < 10) ? "0" + h : h;
            m = (m < 10) ? "0" + m : m;
            s = (s < 10) ? "0" + s : s;

            var time = h + ":" + m + ":" + s + "" + session;

            var today = date.toJSON().slice(0, 10);
            // var month = months[date.getMonth()];
            var month = date.getMonth() + 1;
            var nDate = month + '/'
                + today.slice(8, 10) + '/'
                + today.slice(0, 4) + " " + time;

            return nDate;
        }
    </script>
</head>
<body>
<div class="container">
    <form id="contact" onsubmit="myForm(event)" method="post">
        <h3>Demo Certificate</h3>
        <h4>Demo Certificate By Dr Digital India</h4>
        <fieldset>
            <input placeholder="Your name" id="name" type="text" tabindex="1" required autofocus>
        </fieldset>
        <fieldset>
            <input placeholder="Your Email Address" id="email" type="email" tabindex="2" required>
        </fieldset>
        <fieldset>
            <button name="submit" type="submit" id="contact-submit" data-submit="...Sending">Submit</button>
        </fieldset>
    </form>
</div>
<div id="elementH"></div>
</body>
</html>