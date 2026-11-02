<!DOCTYPE html>
<html lang="{surmeyHtmlLanguage()}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/favicon.png" sizes="16x16 32x32" type="image/png">
    <title>{$title}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    {njs("sdpower/sdeasy")|noescape}
    <style>
        .form-check-input {
            min-width: 1em;
            min-height: 1em
        }
    </style>
</head>

<body class="container py-3">
    <div id="result" class="position-fixed start-0 top-0 p-1 w-100 z-1"></div>
    <form role="form" action="/answer-survey" method="post" class="list-group shadow-sm rounded-4 overflow-hidden border relative" data-content="#result">
        <div class="bg-danger list-group-item list-group-item-action border-0 border-bottom"></div>
        <div class="list-group-item bg-light list-group-item-action border-0 border-bottom fs-6 p-4 text-muted">
            <!-- TITLE START -->
            <div class="cBGGJ OIC90c" dir="auto">Değerli HAUS Grup Çalışanları,<div><br></div>
                <div>01.07.2023 tarihinde çalışma süremizi haftalık 40 saat olarak belirlemiştik. <br>Yeni uygulamamız ile birlikte <b>yeni çalışma saati aralıklarımızın</b> duyurusunu da gerçekleştirmiştik.&nbsp;</div>
                <div><span><br></span></div>
                <div>Sizlerden gelen taleplere istinaden, <b>çalışma saati aralıklarımız hakkında sizlere mevcut uygulamamızın yanı sıra bir başka model önerisini de sunmak istedik. <br></b><br>Aşağıdaki yönlendirmelere istinaden ankete katılımınızı rica ederiz.</div>
                <div><br></div>
                <div>Saygılarımızla.</div>
                <div><b>HAUS IK</b></div>
                <div>
                    <ul>
                        <li><span><i><b>Mevcut çalışma saati aralıkları uygulamamızın devam etmesini isteyen</b></i> çalışanlarımız</span><b> Seçenek - 1</b><span>'i işaretleyebilir.<br><br></span></li>
                        <li><span><i><b>Önerilen diğer çalışma saati aralıkları modelinin uygulanmasını isteyen</b></i> çalışanlarımız</span><b> Seçenek - 2</b><span>'yi işaretleyebilir.</span></li>
                    </ul>
                </div>
                <div><br></div>
            </div>
            <!-- TITLE END -->
        </div>
        <label for="answer1" class="user-select-none p-4 list-group-item list-group-item-action border-0 border-bottom">
            <div class="d-flex">
                <input id="answer1" class="align-self-start form-check-input me-3" type="radio" name="answer" value="0">
                <h5 class="mb-1">Seçenek 1 - Mevcut Çalışma Saati Aralıkları Uygulaması</h5>
            </div>

            <div class="mt-4 table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Çalışma Şekli</th>
                            <th>Giriş Saati</th>
                            <th>Yemek Arası</th>
                            <th>Çıkış Saati</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Tek Vardiye ve Ofis Çalışanları</th>
                            <td>08:30</td>
                            <td>12:00 - 13:00</td>
                            <td>17:30</td>
                        </tr>
                        <tr>
                            <th scope="row">1. Vardiya</th>
                            <td>00:30</td>
                            <td>04:00 - 05:00</td>
                            <td>08:30</td>
                        </tr>
                        <tr>
                            <th scope="row">2. Vardiya</th>
                            <td>08:30</td>
                            <td>11:30 - 12:30</td>
                            <td>16:30</td>
                        </tr>
                        <tr>
                            <th scope="row">3. Vardiya</th>
                            <td>16:30</td>
                            <td>20:00 - 21:00</td>
                            <td>00:30</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </label>
        <label for="answer2" class="user-select-none p-4 list-group-item list-group-item-action border-0 border-bottom">
            <div class="d-flex">
                <input id="answer2" class="align-self-start form-check-input me-3" type="radio" name="answer" value="1">
                <h5 class="mb-1">Seçenek 2 - Alternatif Diğer Çalışma Saati Aralıkları Uygulaması</h5>
            </div>

            <div class="mt-4 table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Çalışma Şekli</th>
                            <th>Giriş Saati</th>
                            <th>Yemek Arası</th>
                            <th>Çıkış Saati</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Tek Vardiye ve Ofis Çalışanları</th>
                            <td>08:00</td>
                            <td>12:00 - 13:00</td>
                            <td>17:00</td>
                        </tr>
                        <tr>
                            <th scope="row">1. Vardiya</th>
                            <td>00:00</td>
                            <td>04:00 - 05:00</td>
                            <td>08:00</td>
                        </tr>
                        <tr>
                            <th scope="row">2. Vardiya</th>
                            <td>08:00</td>
                            <td>11:30 - 12:30</td>
                            <td>16:00</td>
                        </tr>
                        <tr>
                            <th scope="row">3. Vardiya</th>
                            <td>16:00</td>
                            <td>20:00 - 21:00</td>
                            <td>00:00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </label>
        <button class="rounded-0 m-0 btn btn-lg btn-danger shadow-sm">DEVAM ET</button>
    </form>
</body>

</html>
