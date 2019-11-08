<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verifikasi Sikendis - Biro Umum - Setda Provinsi Banten</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="stylesheet" href="/styles/monoland.css">
</head>

<style>
    [v-cloak] {
        display: none;
    }
</style>

<body>
    <div data-app="true" class="v-application v-application--is-ltr theme--light">
        <div class="v-application--wrap">
            <main class="v-content">
                <div class="v-content__wrap">
                    {{ $data->id }}
                </div>
            </main>
        </div>
    </div>

    <script>
        window.errors = null;
    </script>

    <noscript>
        <div class="application">
            <div class="application--wrap">
                <div class="v-content">
                    <div class="v-content__wrap">
                        <div class="container fluid fill-height">
                            <div class="layout align-center justify-center">
                                <div class="subheading font-weight-thin">
                                    <span class="d-block">Mohon maaf, aplikasi tidak dapat bekerja tanpa JavaScript aktif.</span>
                                    <span class="d-block" style="text-align: center;">Silahkan aktifkan untuk melanjutkan.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </noscript>

    <!-- <script src="/scripts/manifest.js"></script> -->
    <!-- <script src="/scripts/vendor.js"></script> -->
    <!-- <script src="/scripts/monoland.js"></script> -->
</body>
</html>