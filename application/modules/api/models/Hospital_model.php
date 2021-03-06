<?php
if(!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class Hospital_model extends CI_Model
{
    
    public function __construct()
    {
        parent::__construct();
	
    }
    
    public function getHospitalList($lat, $long, $notIn, $isemergency, $radius, $isAmbulance, $isInsurance,  $isHealtPkg,  $rating, $userId, $search = null,$cityId=null) {
        $lat = isset($lat) ? $lat : '';
        $long = isset($long) ? $long : '';

        $notIn = isset($notIn) ? $notIn : '';
        
        $where = array('hospital_deleted' => 0);
        if($isemergency != '' && $isemergency != NULL && $isemergency == 1){
            $where['qyura_hospital.isEmergency'] = $isemergency;
        }
       
         
        
        // having array
          
          $ambulance = '';
          $ambulance = ', (SELECT count(ambulance_id) from qyura_ambulance where ambulance_usersId = hospital_id) as isAmbulance';
         if($isAmbulance != '' && $isAmbulance != NULL && $isAmbulance == 1){
            
             $having['isAmbulance !='] = 0;
          }
          
         $insurance = '';
          $insurance = ',  (SELECT count(hospitalInsurance_id) from qyura_hospitalInsurance where hospitalInsurance_hospitalId = hospital_id) as isInsurance';
         if($isInsurance != '' && $isInsurance != NULL && $isInsurance == 1){
             $having['isInsurance !='] = 0;
          }
          
           $healtPkg = '';
            $healtPkg = ', (SELECT count(hospitalPackage_id) from qyura_hospitalPackage where hospitalPackage_hospitalId = hospital_id) as isHealtPkg';
           if($isHealtPkg != '' && $isHealtPkg != NULL && $isHealtPkg == 1){
             $having['isHealtPkg !='] = 0;
          }
          
        if($rating != '' && $rating != NULL && $rating != 0 ){
             $having['rat'] = number_format($rating,1);
         }
         
         

        $this->db->select('hospital_usersId as userId,hospital_id as id, (CASE WHEN(fav_userId is not null ) THEN fav_isFav ELSE 0 END) fav, hospital_address as adr ,hospital_name name, hospital_phn phn, hospital_lat lat, hospital_long long, qyura_hospital.modifyTime upTm, hospital_img imUrl, (
                6371 * acos( cos( radians( ' . $lat . ' ) ) * cos( radians( hospital_lat ) ) * cos( radians( hospital_long ) - radians( ' . $long . ' ) ) + sin( radians( ' . $lat . ' ) ) * sin( radians( hospital_lat ) ) )
                ) AS distance, Group_concat(DISTINCT qyura_specialities.specialities_name order by specialities_name) as specialities, isEmergency '.$ambulance.' '.$insurance.' '.$healtPkg.'
,(
CASE 
 WHEN (reviews_rating is not null AND qyura_ratings.rating is not null) 
 THEN
      ROUND( (AVG(reviews_rating+qyura_ratings.rating))/2, 1)
 WHEN (reviews_rating is not null) 
 THEN 
      ROUND( (AVG(reviews_rating)), 1)
 WHEN (qyura_ratings.rating is not null) 
 THEN
      ROUND( (AVG(qyura_ratings.rating)), 1)
 END)
 AS `rat` ')
                
                ->from('qyura_hospital')
                
                ->join('qyura_hospitalSpecialities', 'qyura_hospitalSpecialities.hospitalSpecialities_hospitalId=qyura_hospital.hospital_id', 'left')
                ->join('qyura_specialities', 'qyura_specialities.specialities_id=qyura_hospitalSpecialities.hospitalSpecialities_specialitiesId', 'left')
                ->join('qyura_reviews', 'qyura_reviews.reviews_relateId=qyura_hospital.hospital_usersId', 'left')
                    
                ->join('qyura_ratings', 'qyura_ratings.rating_relateId=qyura_hospital.hospital_usersId', 'left')
                
                ->join('qyura_fav', 'qyura_fav.fav_relateId = qyura_hospital.hospital_usersId AND fav_userId = '.$userId.'  ', 'left')
                
                ->where($where)
                
                ->where_not_in('qyura_hospital.hospital_id', $notIn)
                ->order_by('distance', 'ASC')
                
                ->limit(DATA_LIMIT);
        if(isset($having) && is_array($having)){
 $this->db->having($having);
        }
        
        if($search != null){
             $this->db->join('qyura_hospitalServices', 'qyura_hospitalServices.hospitalServices_hospitalId = qyura_hospital.hospital_id', 'left');
             $this->db->join('qyura_hospitalDiagnosticsCat', 'qyura_hospitalDiagnosticsCat.hospitalDiagnosticsCat_hospitalId = qyura_hospital.hospital_id', 'left');
             $this->db->join('qyura_diagnosticsCat', 'qyura_diagnosticsCat.diagnosticsCat_catId = qyura_hospitalDiagnosticsCat.hospitalDiagnosticsCat_diagnosticsCatId', 'left');
             
//             $array = array('hospital_name' => $search, 'hospital_address' => $search, 'specialities_name' => $search, 'hospitalServices_serviceName' => $search, 'diagnosticsCat_catName' => $search);
             //$this->db->or_like($array); 
             
            $searchParams =  array('hospital_name' , 'hospital_address' , 'specialities_name' , 'hospitalServices_serviceName' , 'diagnosticsCat_catName' );
             
             foreach ($searchParams as $params){
             if($params == 'hospital_name') {
                $this->db->like($params, $search);
                } else {
                    $this->db->or_like($params, $search);
                } 
             }
             
         }

        
        if ($cityId != NULL) {
            $cityCon = array('hospital_cityId' => $cityId);
            $this->db->where($cityCon);
        } else {
            
            $havingRadius = array('distance <=' => $radius);
            $this->db->having($havingRadius);
        }
        
        $this->db->group_by('hospital_id');
        
        $response = $this->db->get()->result();
       //dump($this->db->last_query()); die();
        //$aoClumns = array("id","fav","rat","adr", "name","phn","lat","lng","upTm","imUrl","specialities");

        $finalResult = array();
        if (!empty($response)) {
            foreach ($response as $row) {

                $finalTemp = array();
                $finalTemp[] = isset($row->id) ? $row->id : "";
                $finalTemp[] = isset($row->fav) ? $row->fav : "";
                $finalTemp[] = isset($row->rat) ? $row->rat : "";
                $finalTemp[] = isset($row->adr) ? $row->adr : "";
                $finalTemp[] = isset($row->name) ? $row->name : "";
                $finalTemp[] = isset($row->phn) ? $row->phn : "";
                $finalTemp[] = isset($row->lat) ? $row->lat : "";
                $finalTemp[] = isset($row->long) ? $row->long : "";
                $finalTemp[] = isset($row->upTm) ? $row->upTm : "";
                $finalTemp[] = isset($row->imUrl) && $row->imUrl != '' ? 'assets/hospitalsImages/' . $row->imUrl : "";
                $finalTemp[] = isset($row->specialities) ? $row->specialities : "";
                $finalTemp[] = isset($row->isEmergency) ? $row->isEmergency : "";
                
                $finalTemp[] = isset($row->isAmbulance) && $row->isAmbulance > 0 ? 1 : 0;
                $finalTemp[] = isset($row->isInsurance) && $row->isInsurance > 0  ? 1  : 0;
                $finalTemp[] = isset($row->isHealtPkg) && $row->isHealtPkg > 0 ? 1 : 0;
                $finalTemp[] = isset($row->userId) ? $row->userId : "";
                $finalResult[] = $finalTemp;
            }
            
            return $finalResult;
        } else {
            return $finalResult;
        }
    }
    
    public function getHosDetails($hospitalId)
    {
        $this->db->select('hospital_id, hospital_usersId, hospital_address, hospital_name, hospital_aboutUs, hospital_phn, hospital_lat, hospital_long, modifyTime');
        $this->db->from('qyura_hospital');
        $this->db->where(array('hospital_id'=>$hospitalId,'hospital_deleted'=>0));
        return $this->db->get()->row();
    }

	

    public function isAmbulance($hospitalId){
        $sql = "SELECT COUNT('ambulance_id') as id
                FROM `qyura_ambulance`
                WHERE `ambulance_deleted` = '0' and `ambulance_usersId` = $hospitalId "; 
        $query = $this->db->query($sql)->row();
        if($query->id){ return 1; }else{ return 0; }
    }
    
    public function getHosGallery($hospitalId)
    {
        $this->db->select('hospitalImages_id, CONCAT("assets/hospitalsImages","/",hospitalImages_ImagesName) as hosImage');
        $this->db->from('qyura_hospitalImages');
        $this->db->where(array('hospitalImages_hospitalId'=>$hospitalId,'hospitalImages_deleted'=>0));
        return $this->db->get()->result();
    }
    
    
    
    function getDiagnosticsCat ($hospitalId,$limit=4) {
        $this->db->select('qyura_diagnosticsCat.diagnosticsCat_catName AS diagnosticsCatName,qyura_hospitalDiagnosticsCat.hospitalDiagnosticsCat_id as hospitalDiagCatTest_diagTestId, CONCAT("assets/diagnosticsCatImages","/",qyura_diagnosticsCat.diagnosticsCat_catImage) as image');
        $this->db->from('qyura_hospitalDiagnosticsCat');
        $this->db->join('qyura_diagnosticsCat','qyura_diagnosticsCat.diagnosticsCat_catId = qyura_hospitalDiagnosticsCat.hospitalDiagnosticsCat_diagnosticsCatId','left');
        $this->db->where(array('qyura_hospitalDiagnosticsCat.hospitalDiagnosticsCat_hospitalId'=>$hospitalId,'qyura_hospitalDiagnosticsCat.hospitalDiagnosticsCat_deleted'=>0));
        $this->db->group_by('qyura_hospitalDiagnosticsCat.hospitalDiagnosticsCat_id');
        if($limit)
            $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    public function getHosServices($hospitalId,$limit=3)
    {
        $this->db->select('hospitalServices_serviceName as serviceName,hospitalServices_deleted,modifyTime,hospitalServices_id');
        $this->db->from('qyura_hospitalServices');
        $this->db->where(array('qyura_hospitalServices.hospitalServices_hospitalId'=>$hospitalId,'hospitalServices_deleted'=> 0));
        if($limit)
        $this->db->limit($limit);
        return $this->db->get()->result();
    }
    
    public function getHosSpecialities($hospitalId,$limit=3)
    {
        $this->db->select('qyura_specialities.specialities_name,qyura_specialities.specialities_id,qyura_specialities.specialities_specialitiesCatId,qyura_specialities.modifyTime,qyura_specialities.specialities_deleted');
        $this->db->from('qyura_specialities');
        $this->db->join('qyura_hospitalSpecialities','qyura_hospitalSpecialities.hospitalSpecialities_specialitiesId=qyura_specialities.specialities_id','left');
        $this->db->where(array('qyura_hospitalSpecialities.hospitalSpecialities_hospitalId'=>$hospitalId,'qyura_hospitalSpecialities.hospitalSpecialities_deleted'=>0,'qyura_specialities.specialities_deleted'=>0));
        if($limit)
        $this->db->limit($limit);
       return $this->db->get()->result();
       // echo $this->db->last_query(); exit;
    }
    
   /* public function getHosHelthPkg($hospitalId)
    {
        $this->db->select('healthPackage_id,healthPackage_packageTitle,healthPackage_packageId,healthPackage_packageTitle,healthPackage_expiryDateStatus,healthPackage_date,healthPackage_bestPrice,healthPackage_discountedPrice,healthPackage_description,healthPackage_deleted,modifyTime');
        $this->db->from('qyura_healthPackage');
        $this->db->where(array('healthPackage_MIuserId'=>$hospitalId,'healthPackage_deleted'=>0));
        return $this->db->get()->result();
    } */
    
    public function getHosHelthPkg($hospitalId)
    {
        $this->db->select('healthPackage_id,healthPackage_packageTitle,healthPackage_packageId,healthPackage_packageTitle,healthPackage_expiryDateStatus,healthPackage_date,healthPackage_bestPrice,healthPackage_discountedPrice,healthPackage_description,healthPackage_deleted,qyura_healthPackage.modifyTime');
        $this->db->from('qyura_healthPackage');
        $this->db->join('qyura_hospitalPackage','qyura_hospitalPackage.hospitalPackage_healthPackageId = qyura_healthPackage.healthPackage_id');
        $this->db->where(array('hospitalPackage_hospitalId'=>$hospitalId,'healthPackage_deleted'=>0));
        $this->db->group_by('healthPackage_id');
        return $this->db->get()->result();
    }
    
    public function getHosReviewCount()
    {
        $sql = "SELECT COUNT('reviews_id') as reviews
                FROM `qyura_reviews`
                WHERE `reviews_deleted` = '0' and `reviews_userId` = '1' "; 
        $query = $this->db->query($sql)->row();
        return $query->reviews;
        
    }
    
    
    public function getHosAvgRating($hospitalUserId)
    {
           $this->db->select('(
                    CASE 
                     WHEN (reviews_rating is not null AND qyura_ratings.rating is not null) 
                     THEN
                          ROUND( (AVG(reviews_rating+qyura_ratings.rating))/2, 1)
                     WHEN (reviews_rating is not null) 
                     THEN 
                          ROUND( (AVG(reviews_rating)), 1)
                     WHEN (qyura_ratings.rating is not null) 
                     THEN
                          ROUND( (AVG(qyura_ratings.rating)), 1)
                     END)
                     AS `rat` ')
                   ->from('qyura_hospital')
                   ->join('qyura_reviews', 'qyura_reviews.reviews_relateId=qyura_hospital.hospital_usersId', 'left')
                    
                   ->join('qyura_ratings', 'qyura_ratings.rating_relateId=qyura_hospital.hospital_usersId', 'left')
                   ->where(array('qyura_hospital.hospital_usersId' => $hospitalUserId));
                   $result = $this->db->get()->row();
                   return isset($result->rat) && $result->rat != '' ? $result->rat : '';
    }
    
    public function getHosDoctors($hospitalId,$hospitalUsersId,$limit=4)
    {
        $this->db->select('doctors_id,doctors_userId,CONCAT("assets/doctorsImages","/",doctors_img) as doctors_img,doctors_fName,doctors_lName,doctor_addr,doctors_phn,doctors_mobile,doctors_27Src,doctors_consultaionFee');
        $this->db->from('qyura_usersRoles');
        $this->db->join('qyura_doctors','qyura_doctors.doctors_userId=qyura_usersRoles.usersRoles_userId','left');
        $this->db->where(array('qyura_usersRoles.usersRoles_parentId'=>$hospitalUsersId,'qyura_usersRoles.usersRoles_roleId'=>ROLE_DOCTORE));
        if($limit)
        $this->db->limit($limit);
        $doctors = $this->db->get()->result();
        
        $doctorResult = array();
        if(!empty($doctors)){
            foreach($doctors as $doctor)
            {
                $doctorTemp = array();
                $doctorTemp['doctors_id'] = $doctor->doctors_id;
                $doctorTemp['userId'] = $doctor->doctors_userId;
                $doctorTemp['img'] = $doctor->doctors_img;
                $doctorTemp['fName'] = $doctor->doctors_fName;
                $doctorTemp['lName'] = $doctor->doctors_lName;
                $doctorTemp['addr'] = $doctor->doctor_addr;
                $doctorTemp['phn'] = $doctor->doctors_phn;
                $doctorTemp['mobile'] = $doctor->doctors_mobile;
                $doctorTemp['Src27'] = $doctor->doctors_27Src;
                $doctorTemp['consultaionFee'] = $doctor->doctors_consultaionFee;
                $doctorTemp['parents'] = $this->getDoctorsRole($doctor->doctors_userId);
                $doctorResult[] = $doctorTemp;
            }
            return $doctorResult;
        }
        
        return $doctorResult;
    }
    
    public function getDoctorsRole($userId)
    {
        $this->db->select('qyura_doctors.doctors_id,qyura_usersRoles.usersRoles_userId,qyura_usersRoles.usersRoles_roleId,qyura_usersRoles.usersRoles_parentId');
        $this->db->from('qyura_usersRoles');
        $this->db->join('qyura_doctors','qyura_doctors.doctors_userId=qyura_usersRoles.usersRoles_userId','left');
        $this->db->where(array('qyura_usersRoles.usersRoles_userId'=>$userId,'qyura_usersRoles.usersRoles_deleted'=>0));
        return $this->db->get()->result();
    }
    
    public function getHosInsurance($hospitalId,$limit=4)
    {
        $this->db->select('insurance_Name,insurance_id,CONCAT("assets/insuranceImages","/",insurance_img)insurance_img,qyura_insurance.modifyTime');
        $this->db->from('qyura_hospitalInsurance');
        $this->db->join('qyura_insurance','qyura_insurance.insurance_id=qyura_hospitalInsurance.hospitalInsurance_insuranceId','right');
        $this->db->where(array('qyura_hospitalInsurance.hospitalInsurance_hospitalId'=>$hospitalId,'qyura_hospitalInsurance.hospitalInsurance_deleted'=>0));
        if($limit)
        $this->db->limit($limit);
        return $this->db->get()->result();
    }
    
    public function getHosAwards($hospitalId,$limit=3)
    {
        $this->db->select('qyura_awards.awards_id,awards_awardsName name,hospitalAwards_awardYear year,qyura_hospitalAwards.modifyTime');
        $this->db->from('qyura_awards');
        $this->db->join('qyura_hospitalAwards','qyura_hospitalAwards.hospitalAwards_awardsId = qyura_awards.awards_id ','left');
        $this->db->where(array('qyura_hospitalAwards.hospitalAwards_hospitalId'=>$hospitalId,'qyura_hospitalAwards.hospitalAwards_deleted'=>0));
        if($limit)
        $this->db->limit($limit);
      return  $this->db->get()->result();
       // echo $this->db->last_query(); exit;
    }
    
    
}
?>
