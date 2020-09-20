<html>
 <head>
  <title>PHP Test</title>

  <link rel="stylesheet" href="./css/output.css">
 </head>
 <body>
     <?php echo '<p>Hello World</p>';
     $version = phpversion();
     print $version;
     ?>
     <br>
     <?php
     echo password_hash("symp-pat0che", PASSWORD_DEFAULT);
     echo "<br>";
     echo password_hash("r1.c4rd", PASSWORD_DEFAULT);
     ?>
     <br>
     <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
         TailwindCSS Button
     </button>
 </body>
</html>
