<?php

namespace App;

class Privileges {
    const ADMIN_HIERARCHY = ['Admin'];
    const DEAN_HIERARCHY = ['Admin', 'Decano'];
    const COORDINATOR_HIERARCHY = ['Admin', 'Decano', 'Coordinator'];
    const PROFESSOR_HIERARCHY = ['Admin', 'Decano', 'Coordinator', 'Profesor'];
}

