<?php
include 'includes/page-common.php';
include 'includes/head.php';
?>
<link rel="stylesheet" href="assets/css/event.css">
<!-- TODO - https://codepen.io/dsalvagni/pen/BLapab KULLAN -->

<body>
    <?php
    setlocale(LC_ALL, 'tr_TR.UTF-8', 'tr_TR', 'tr', 'trk', 'turkish');
    include 'includes/nav-bar.php';

    $kullanici_id = 0;

    if (isset($_GET["id"])) {
        $kullanici_id = UrlIdFrom("id");
    } else if (isset($_GET["user"])) {
        $kullanici_id = UrlIdFrom("user");
    }

    if ($kullanici_id == NULL || $kullanici_id == 0) {
        header('Location: index.php');
    }

    echo $kullanici_id;


    $ayarlar = KullaniciAyarlariniGetirById($kullanici_id);
    $kullanici_detail = KullaniciBilgileriniGetirById($kullanici_id);

    //$yeni_etkinlikler=KullaniciYeniEtkinlikleriniGetir($kullanici_id);
    //$eski_etkinlikler=KullaniciEskiEtkinlikleriniGetir($kullanici_id);

    $eski_etkinlikler = [];
    if ($ayarlar["gecmis_private"] == "no")
        $eski_etkinlikler = KullaniciEskiEtkinlikleriniGetir($kullanici_id);

    $gelecek_etkinlikler = [];
    if ($ayarlar["gelecek_private"] == "no")
        $gelecek_etkinlikler = KullaniciYeniEtkinlikleriniGetir($kullanici_id);
    ?>
    <style>
    .profile-detail {
        padding: 2rem 1rem;
        margin-bottom: 2rem;
        background-color: #e9ecef;
        border-radius: .3rem;
    }

    .profile-name {
        font-size: 28px;
        line-height: 1.25;
        stroke: transparent;
        fill: rgba(0, 0, 0, .87);
        color: rgba(0, 0, 0, .87);
        font-weight: 600;
        letter-spacing: -.02em;
    }

    .profile-pic {
        /* display: flex; */
        justify-content: center;
        text-align: center;
        height: 250;
        width: 250;
    }

    .profile-img {
        max-width: 240px;
        max-height: 100%;
    }
    </style>
    <!--Profil Kartı -->
    <div class="container">
        <div class="profile-detail">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <div class="container" style="border-bottom:1px solid black">
                        <h2 class="profile-name">
                            <?php echo $kullanici_detail["adi"] . " " . $kullanici_detail["soyadi"]  ?>
                        </h2>
                    </div>
                    <br />
                    <ul class="container details" style="list-style: none;">
                        <li>
                            <p>
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo "Konum: " . $ayarlar["sehir"]  ?>
                            </p>
                        </li>
                        <li>
                            <p>
                                <i class="fas fa-calendar-alt"></i>
                                <?php
                                echo "Üyelik Tarihi: ";
                                echo turkcetarih_formati("d M Y ", $kullanici_detail["kayit_tarihi"]);
                                ?>
                            </p>
                        </li>
                        <li>
                            <p>
                                <i class="fas fa-clock"></i>
                                <?php
                                echo "Son Ziyaret : ";
                                echo turkcetarih_formati("d M Y ", $kullanici_detail["son_giris_tarihi"]);
                                ?>
                            </p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="profile-pic">
                        <img src="files/profile/<?php echo $kullanici_detail["id"] ?>.png"
                            alt="<?php echo $kullanici_detail["adi"] . " " . $kullanici_detail["soyadi"] ?>"
                            class="profile-img" onerror="this.onerror=null; this.src='files/profile/profile.png'">
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div>
            <div class="row">
                <div class="col-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home"
                            role="tab" aria-controls="v-pills-home" aria-selected="true">
                            Katılacağı Etkinlikler
                        </a>
                        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile"
                            role="tab" aria-controls="v-pills-profile" aria-selected="false">
                            Katıldığı Etkinlikler
                        </a>
                    </div>
                </div>
                <div class="col-9">
                    <div class="tab-content" id="v-pills-tabContent">

                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                            aria-labelledby="v-pills-home-tab">
                            <?php
                            $gelecek_etkinlikler_count = 0;
                            if ($gelecek_etkinlikler != NULL)
                                $gelecek_etkinlikler_count = count($gelecek_etkinlikler);

                            if ($gelecek_etkinlikler != NULL && $gelecek_etkinlikler_count > 0) {
                                for ($i = 0; $i < count($gelecek_etkinlikler); $i++) {
                                    $etkinlik = $gelecek_etkinlikler[$i];
                                    ?>
                            <div class="card row mx-2 mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <span
                                            class="badge badge-secondary event-type"><?php echo $etkinlik["tip"] ?></span>
                                        <?php
                                                $isim =  $etkinlik["isim"];
                                                $id =  $etkinlik["id"];
                                                echo "<a href='event.php?event=$id'> $isim </a>"
                                                ?>
                                        <p class="card-text"
                                            style="     float: right; font-size: medium; margin-right: 25px;">
                                            <i class="fas fa-clock"></i>
                                            <?php
                                                    echo turkcetarih_formati("d M Y", $etkinlik["tarih"]);
                                                    ?></p>
                                    </h5>
                                    <!-- <p class="card-text"> <?php echo $etkinlik["k_aciklama"] ?></p> -->
                                </div>
                            </div>
                            <?php }
                        } else { ?>
                            <div class="alert alert-warning" role="alert">
                                <?php echo $kullanici_detail["adi"] ?> gelecekte herhangi bir etkinliğe katılmıyor.
                            </div>
                            <?php  }  ?>
                        </div>
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                            aria-labelledby="v-pills-profile-tab">
                            <?php
                            if ($eski_etkinlikler != NULL  && count($eski_etkinlikler) > 0) {
                                for ($i = 0; $i < count($eski_etkinlikler); $i++) {
                                    $etkinlik = $eski_etkinlikler[$i];
                                    ?>
                            <div class="card row mx-2 mb-3">
                                <div class="card-body" style="">
                                    <h5 class="card-title">

                                        <span
                                            class="badge badge-secondary event-type"><?php echo $etkinlik["tip"] ?></span>
                                        <?php
                                                $isim =  $etkinlik["isim"];
                                                $id =  $etkinlik["id"];
                                                echo "<a href='event.php?event=$id'> $isim </a>"
                                                ?>
                                        <p class="card-text"
                                            style="     float: right; font-size: medium; margin-right: 25px;">
                                            <i class="fas fa-clock"></i>
                                            <?php echo turkcetarih_formati("d M Y", $etkinlik["tarih"]); ?>
                                        </p>
                                    </h5>
                                    <!-- <p class="card-text"> <?php echo $etkinlik["k_aciklama"] ?></p> -->
                                </div>
                            </div>
                            <?php }
                        } else { ?>
                            <div class="alert alert-warning" role="alert">
                                <?php echo $kullanici_detail["adi"] ?> daha önce bir etkinliğe katılmadı.
                            </div>
                            <?php  }  ?>
                        </div>
                    </div>
                </div>
            </div>
          
        </div>


</body>