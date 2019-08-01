<?php

include_once "wxBizDataCrypt.php";


$appid = 'wxb2418420ae2cf37c';
$sessionKey = 'HIXyiM39RTw/Rhn7diLZTA==';

$encryptedData="MU0y/92EaHvJUVaGkz0t8ixYORUXrC3ENV+qT0olCIxJ2PBd/qemWwAp+lfD6KoM1Nh65enaEOYUJYBhcCerWigBI0yIkyPTyjQ0rQkgvT12fSZdcJ8nGfFUlgrj9Fee6GxUD21tdg9Hc44NFyDS3SIWaE+ky4LriACyutLDiqVQxzWJe8bv6ZaadG6eefVLHsOr6fNUoZ7kpO2XA5fMbFFSDoxVM62PZBKiPGZhlOOmNnGF+BRTKclumJy1fSK3DFapp2THpDDwEKcZzsNuWI0N7lu2SfZDM9Z4XfKJuZ4A5TbNZMa96reJxp+R6drsNJVEBjbS+KqkK5wDEGx0fBWhSHlXLOHWI19B/tL4/OJHrRjmHYPCwq3N9Zpx3fiktBfIfF7ImO3A95MTuKWGOrPyPxvN7U9+XgYGpnKFqszMmtcu7vpd4/N4sF7ZggHuKHfgw2sbxoD6ckNcp6E3jHODTSOe5rfryCn894jGeuRlJJj7l0k06OInHj/7llA3OkuK0wey1aJkmMcl7z4j7A==";

$iv = "EWhkEj6DtTru8cZFCyzCyg==";

$pc = new WXBizDataCrypt($appid, $sessionKey);
$errCode = $pc->decryptData($encryptedData, $iv, $data );

if ($errCode == 0) {
    print($data . "\n");
} else {
    print($errCode . "\n");
}
