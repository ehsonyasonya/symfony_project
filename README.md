# **Appointment Management System (Symfony-based)**  

## **Description**  
This project is an **Appointment Management System** built with **Symfony**, designed for scheduling and managing appointments between clients and employees. The system is currently in development and requires further refinements.  

## Installation

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Clone repository
3. Run `docker compose build --no-cache` to build fresh images
4. Run `docker compose up --pull always -d --wait` to set up and start a fresh Symfony project
5. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
6. Run `docker compose down --remove-orphans` to stop the Docker containers.

### **Key Features**  

✅ **User Roles & Authentication**  
- Uses JWT tokens for authentication.  
- **Admins manage users, employees, and services** and are the only ones allowed to create appointments.  
- Clients can view their scheduled appointments but cannot create them.  

✅ **Appointment Booking & Management**  
- **Only admins can create appointments** for clients with employees.  
- Employees have individual schedules to prevent double bookings.  
- Prevents booking an employee at the same time slot twice.  

✅ **Entity Relationships**  
- **Users** (Clients & Admins)  
- **Employees** (Linked to users)  
- **Appointments** (Connects clients, employees, and services)  
- **Services** (Bookable services)  
- **Schedule** (Manages employees' working hours)  

✅ **Custom Error Handling & Validation**  
- Ensures valid user input and prevents scheduling conflicts.  
- Returns appropriate error messages for missing or invalid data.  

✅ **Token-Based Authorization (JWT)**  
- **Admins must authenticate using a JWT token** to create appointments.  
- Protects endpoints from unauthorized access. 

### **Console Commands**  

The system provides two Symfony console commands for quick management:
##### **1. Add a New User** and **2. Add a New Service**

### **Postman Collection**  
A **Postman collection** with pre-configured requests for testing the API is available in the project folder:  
📂 **symfony_project.postman_collection.json** 

***Next Steps for Development***
- Improve unit tests for core functionalities.
- Enhance error handling and response messages.
- Implement a frontend for easier interaction.

## License

This project is licensed under the MIT License – you are free to use, modify, and distribute it as long as proper credit is given.

## Credits

Created by Sofiya Karduba, [@ehsonyasonya](https://github.com/ehsonyasonya)
