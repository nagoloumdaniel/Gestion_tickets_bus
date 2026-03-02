    <?php 
        include "../conn.php"; 
        $users = "SELECT count(*) As total FROM Utilisateurs WHERE is_deleted = 'false' ";
        $agences = "SELECT count(*) As total1 FROM Agences WHERE is_deleted = 'false' ";
        $reservatons = "SELECT count(*) As total2 FROM Reservations WHERE is_deleted = 'false' ";
        $query = "SELECT SUM(Montant_paye) AS somme FROM paiements WHERE is_deleted = 'false'";

        $user = $conn->query($users);
        $agence = $conn->query($agences);
        $reservaton = $conn->query($reservatons);
        $result = $conn->query($query);

        $row1 = $user->fetch_assoc();
        $row2 = $agence->fetch_assoc();
        $row3 = $reservaton->fetch_assoc();
        $row = $result->fetch_assoc();

        $count1 = $row1['total'];
        $count2 = $row2['total1'];
        $count3 = $row3['total2'];
        $somme = $row['somme'];

    ?>
    
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Revenus (TOTAL)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">XAF <?php echo $somme; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Reservations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $count3; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Utilisateurs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo "$count1"; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Agences </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo "$count2"; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-landmark fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
