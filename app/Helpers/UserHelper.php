<?php

namespace App\Helpers;

use App\Models\User;

class UserHelper
{
    /**
     * Get user role information
     * 
     * @param User $user
     * @return array
     */
    public static function getUserRole(User $user): array
    {
        $isAdmin = $user->isAdmin() === true;
        $doctorId = $user->doctor_id;
        $hasValidDoctorId = ($doctorId !== null && $doctorId !== 0 && $doctorId !== '');
        $isDoctor = $hasValidDoctorId && !$isAdmin;
        $isRegularUser = !$isAdmin && !$isDoctor;
        
        return [
            'isAdmin' => $isAdmin,
            'isDoctor' => $isDoctor,
            'isRegularUser' => $isRegularUser
        ];
    }
    
    /**
     * Get dashboard route based on user role
     * 
     * @param User $user
     * @return string
     */
    public static function getDashboardRoute(User $user): string
    {
        $role = self::getUserRole($user);
        
        if ($role['isAdmin'] || $role['isDoctor']) {
            return 'admin.dashboard';
        }
        
        return 'user.dashboard';
    }
}


