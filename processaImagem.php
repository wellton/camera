<?php
   //usando a bibliteca do OpenCV
   use OpenCV\Image as Image;
   
   //Exibindo texto na tela
   echo "<h1>Imagem Processada</h1>";

   //Função que detecta e marca com uma linha azul a face
   //gravando em arquivo o arquivo com a face detectada
   //e uma imagem apenas com a face recortada da original
   detectaFace($_GET["imagem"].".jpg");
   
   //Exibindo a imagem com a face detectada na tela
   echo " <br><img src='".$_GET['imagem']."FaceMarcada.jpg'>&nbsp;&nbsp;&nbsp;";
   
   //Exibindo a face na tela 
   echo "<img src='".$_GET['imagem']."FaceSelecionada.jpg'>";

   //função detectaFace
   function detectaFace($imagem){
      //carregando pixels da imagem via OpenCV e jogando
      //valores na variávei $i
      $i = Image::load($imagem, Image::LOAD_IMAGE_COLOR);

      //chamada do método HaarDetectObjects para
      //identificar as faces... de acordo com o
      //treinamento do arquivo haarcascade_frontalface_alt.xml
      //disponibilizado pelo OpenCV
      $facesIdentificadas = $i->haarDetectObjects("haarcascade_frontalface_alt.xml");

      //joga as faces identificadas dentro do foreach
      //para que os retangulos sejam desenhados em cima
      //de x, y, largura e altura identificados na imagem 
      foreach ($facesIdentificadas as $r) {
         //chama o método rectangle do OpenCV para desenhar o retangulo na face de acordo com os dados identificados
         $i->rectangle($r['x'], $r['y'], $r['width'], $r['height']);

         //exibe na tela os valores identificados
	 echo "x=".$r['x']."<br>y=". $r['y']."<br>largura=". $r['width']."<br>altura=". $r['height'];

        //Chama função para cortar a face identificada da imagem e gravar em arquivo
        cortarFace($imagem, $r['x'], $r['y'], $r['height'], $r['width']);
     }
   
     //chamada do método para salvar a imagem original com a face identificada com o retangulo azul
     $i->save($_GET["imagem"]."FaceMarcada.jpg");
   
     return $i;
}

//função cortar Face
function cortarFace($imagem, $esquerda, $emCima, $direita, $emBaixo){

   // Pega dimensões da imagem original
   list($larguraOriginal, $alturaOriginal) = getimagesize($imagem);

   // Redefinir dimensões da imagem para colocar só a face identificada
   $canvas = imagecreatetruecolor($direita, $emBaixo);
   $imagemAtual = imagecreatefromjpeg($imagem);

   imagecopy($canvas, $imagemAtual, 0, 0, $esquerda, $emCima, $larguraOriginal, $alturaOriginal);
  
 imagejpeg($canvas, $_GET["imagem"]."FaceSelecionada.jpg", 100);

    //TERmiNA cORTE
}
?>
