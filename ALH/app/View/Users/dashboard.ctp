<?php
echo "Welcome " . AuthComponent::user("first_name") . " " . AuthComponent::User("last_name") . "<br/>";
echo $this->html->link("Edit Profile", array("controller" => "Users", "action" => "register")) . "<br/>";
echo $this->html->link("View Profile / My Account", array("controller" => "Users", "action" => "viewProfile")) . "<br/>";
echo $this->html->link("Change Password", array("controller" => "Users", "action" => "changePassword")) . "<br/>";
echo $this->html->link("Create Event", array("controller" => "Events", "action" => "createEvent")) . "<br/>";
echo $this->html->link("Calendar", array("controller" => "Events", "action" => "calendar")) . "<br/>";
echo $this->html->link("My Calendar", array("controller" => "Events", "action" => "myCalendar")) . "<br/>";
echo $this->html->link("Brands", array("controller" => "Brands", "action" => "brandList")) . "<br/>";
echo $this->html->link("My Subscription", array("controller" => "Brands", "action" => "mySubcription")) . "<br/>";
echo $this->html->link("Billing Info", array("controller" => "Users", "action" => "BillingInfo")) . "<br/>";
echo $this->html->link("MyList", array("controller" => "MyLists", "action" => "myList")) . "<br/>";
echo $this->html->link("My Event List", array("controller" => "Events", "action" => "MyEventList")) . "<br/>";
echo $this->html->link("A la Carte Promo Services", array("controller" => "Services", "action" => "alacartePromotionalService")) . "<br/>";
echo $this->html->link("Promotional Packages", array("controller" => "Services", "action" => "promotionalPackages")) . "<br/>";
echo $this->html->link("Public list for Offer ", array("controller" => "MyLists", "action" => "premiumList")) . "<br/>";
echo $this->html->link("Logout", array("controller" => "users", "action" => "logout"));
?>