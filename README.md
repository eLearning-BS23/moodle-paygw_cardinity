# Moodle Payment Gateway Cardinity plugin

Cardinity is a safe and cost-effective online payment solution for e-commerce businesses selling various products or providing services. Your customers will be able to buy products online with ease and security. Cardinity is available for EU merchants of different types: from low to high risk, from businesses to sole proprietors, from retail products to digital goods.

Cardinity is the best and simplest way to accept payments online in Europe. Cardinity aims to expand the infrastructure of internet commerce by making it easy to process transactions and manage an online business. For more detail about `Cardinity` please visit https://www.Cardinity.com/.

## Features
- GLOBAL PAYMENTS
- ONE-CLICK PURCHASES
- FRIENDLY PRICE
- EASY INTEGRATION
- Secure and Fast Payments

## Configuration

You can install this plugin from [Moodle plugins directory](https://moodle.org/plugins) or can download from [Github](https://github.com/eLearning-BS23/moodle-paygw_cardinity).

You can download zip file and install or you can put file under payment-> gateway-> cardinity

## Plugin Global Settings
### Go to
```
Dashboard->Site Administration->Plugins->Payment Gateways->cardinity settings
```
In this page you can add surcharge for the payments. After installing the plugin you'll automatically redirected to this page.

![image](https://user-images.githubusercontent.com/8987681/153187968-684b8b15-2e47-437d-bed9-508ee3023c4e.png)

## Configuring the Cardinity Gateway:
### Step: 1
```
Dashboard / Site administration / Plugins / Payment gateways / Manage payment gateways / Gateways->Cardinity settings
```
![image](https://user-images.githubusercontent.com/8987681/153188798-790541d9-4d25-4605-80f8-bf965e61c6f9.png)

Enable Cardinity to the payment gateways

### Step: 2
```
Dashboard->Site Administration->Plugins->Payment Gateways->Cardinity settings
```
![image](https://user-images.githubusercontent.com/40598386/189609353-88a854a1-239d-4b64-8712-2318a239264b.png)

- Insert the Cardinity project ID provided by Cardinity
- Insert the Cardinity project secrect key provided by Cardinity
- Insert the Cardinity consumer key provided by Cardinity
- Insert the Cardinity consumer secrect key provided by Cardinity
- Click the "save changes" button to save the information

### Step: 3

Go to the Manage Enrolment Plugins section from the site administration
```
Dashboard->Site Administration->Plugins->Enrolments->Manage Enrol Plugins
```

![image](https://user-images.githubusercontent.com/97436713/153135098-3492f3d1-9dc6-401d-81b1-ad86f6f01494.png)

Enable Enrolment on payment by clicking the eye icon.

## Enrolment Settings for Course:

Now click on the course page and add an enrolment method Enrolment of Payment.

![image](https://user-images.githubusercontent.com/97436713/153138641-93f67f96-9bc1-44bf-afbd-8641b0bd8821.png)

and fill up this form below to set the amount of money and currency for the course payment

![image](https://user-images.githubusercontent.com/97436713/153138610-ff83dcde-ebc2-430f-a870-2b612203a576.png)

This is how it looks like from a student's perspective:

![image](https://user-images.githubusercontent.com/97436713/153136854-31f92d49-9161-4922-90ca-c5d4224228c8.png)

Select the Payment Type- Cardinity the surcharge is added with the course payment amount

![image](https://user-images.githubusercontent.com/8987681/153190246-2b1e5d4e-8d0c-49e6-885a-774f0428d1cf.png)


Give details of your card:
![image](https://user-images.githubusercontent.com/8987681/153190520-a92d712e-532d-4c9a-bbd2-ee206d5ff1f9.png)


If your payment is successful then you'll be enrolled in the course. Please make sure your moodle account have country added to it. 

## Author
- [Brain Station 23 Ltd.](https://brainstation-23.com)

## License
This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see [GNU License](http://www.gnu.org/licenses/).
