#include <iostream>
#include <fstream>
#include <chrono>
#include <thread>

using namespace std;

enum TERMUX_CAMERA_ID {
	FRONT,	//1
	REAR	//0
};

enum TERMUX_REQUEST {
	BATTERY_STATUS,
	TAKE_REAR_PHOTO,
	TAKE_FRONT_PHOTO,
	SEND_SMS,
	CALL_PHONE_NUMBER,
	TORCH_ON,
	TORCH_OFF,
	NONE
};

string work_directory = "/data/data/";		//WORK_DIRECTORY
int SLEEP_TIME = 5;				//SECONDS
bool PREVIOUS_TORCH_STATE = 0;			//TOGGLE TORCH STATUS OBJECT

TERMUX_REQUEST check_request (string directory);
void serve_request(TERMUX_REQUEST request);
void process_battery_request_to_file(string filename);
void process_camera_request_to_file(string filename, TERMUX_CAMERA_ID camera_id);
void process_send_sms_request_from_file(string filename);
void process_call_phone_number_request_from_file(string filename);
void process_torch_request(bool on);
void clear_status(string filename);
void setup();

bool read_termux_file(string filename) {
	ifstream file (filename);
	int request = 0;
	file >> request;
	file.close();
	return (request == 1); 

}

TERMUX_REQUEST check_request (string directory) {

	if (read_termux_file(directory + "battery"))
		return TERMUX_REQUEST::BATTERY_STATUS;
	if (read_termux_file(directory + "rear_photo"))
		return TERMUX_REQUEST::TAKE_REAR_PHOTO;
	if (read_termux_file(directory + "front_photo"))
		return TERMUX_REQUEST::TAKE_FRONT_PHOTO;
	if (read_termux_file(directory + "send_sms"))
		return TERMUX_REQUEST::SEND_SMS;
	if (read_termux_file(directory + "call_phone_number"))
		return TERMUX_REQUEST::CALL_PHONE_NUMBER;
	if (read_termux_file(directory + "torch")) {
		if (PREVIOUS_TORCH_STATE) {
			PREVIOUS_TORCH_STATE = false;
			return TERMUX_REQUEST::TORCH_OFF;
		} else {
			PREVIOUS_TORCH_STATE = true;
			return TERMUX_REQUEST::TORCH_ON;
		}
	}

	return TERMUX_REQUEST::NONE;
}

void serve_request(TERMUX_REQUEST request) {
	switch (request) {
		case TERMUX_REQUEST::BATTERY_STATUS:
			process_battery_request_to_file(work_directory + "battery_result");
			break;
		case TERMUX_REQUEST::TAKE_REAR_PHOTO:
			process_camera_request_to_file(work_directory + "rear_photo_result", TERMUX_CAMERA_ID::REAR);
			break;
		case TERMUX_REQUEST::TAKE_FRONT_PHOTO:
			process_camera_request_to_file(work_directory + "front_photo_result", TERMUX_CAMERA_ID::FRONT);
			break;
		case TERMUX_REQUEST::SEND_SMS:
			process_send_sms_request_from_file(work_directory + "send_sms");
			break;
		case TERMUX_REQUEST::CALL_PHONE_NUMBER:
			process_call_phone_number_request_from_file(work_directory + "call_phone_number");
			break;
		case TERMUX_REQUEST::TORCH_ON:
			process_torch_request(true);
			break;
		case TERMUX_REQUEST::TORCH_OFF:
			process_torch_request(false);
			break;
	}
}

void process_battery_request_to_file(string filename) {

	clear_status (work_directory + "battery");
	string command ("termux-battery-status > " + filename);
	system(command.c_str());
}

void process_camera_request_to_file(string filename, TERMUX_CAMERA_ID camera_id) {

	if (camera_id == TERMUX_CAMERA_ID::FRONT) {
		clear_status (work_directory + "front_photo");
		string command ("termux-camera-photo -c 1 " + filename);
		system(command.c_str());
	} else if (camera_id == TERMUX_CAMERA_ID::REAR) {
		clear_status (work_directory + "rear_photo");
		string command ("termux-camera-photo -c 0 " + filename);
		system(command.c_str());
	}
}

void process_send_sms_request_from_file(string filename) {

	ifstream file (filename);
	int request = 0;
	string phone_number;
	string message ("Hello this is a test message from yeda dabba server. Please ignore it!!");

	file >> request;
	file >> phone_number;
	file.close();

	clear_status (work_directory + "send_sms");

	//Guard for minimal validity
	if (request != 1 && phone_number.length() != 10)
		return

	string command ("termux-sms-send -n " + phone_number + " " + message);
	system(command.c_str());
	
}

void process_call_phone_number_request_from_file(string filename) {

	ifstream file (filename);
	int request = 0;
	string phone_number;

	file >> request;
	file >> phone_number;
	file.close();

	clear_status (work_directory + "call_phone_number");

	//Guard for minimal validity
	if (request != 1 && phone_number.length() != 10)
		return

	string command ("termux-telephony-call " + phone_number);
	system(command.c_str());
	
}

void process_torch_request(bool on = false) {

	if (on) {
		string command ("termux-torch on");
		system(command.c_str());
	} else {
		string command ("termux-torch off");
		system(command.c_str());
	}

	clear_status (work_directory + "torch");

}

void clear_status(string filename) {
	ofstream file (filename, ios_base::trunc);
	file << 0;
	file.close();	
}

void setup() {
	clear_status (work_directory + "battery");
	clear_status (work_directory + "rear_photo");
	clear_status (work_directory + "front_photo");
	clear_status (work_directory + "send_sms");
	clear_status (work_directory + "call_phone_number");
	clear_status (work_directory + "torch");
}

int main (int argc, char *argv[]) {

	//Phone Controller
	//termux-battery-status
	//termux-camera-photo 0-rear 1-front
	//termux-sms-send -n number text
	//termux-telephony-call number
	//termux-torch on|off
	
	setup();
	TERMUX_REQUEST request = TERMUX_REQUEST::NONE;

	while (1) {
		do {
			request = check_request(work_directory);
			serve_request(request);
		} while (request == TERMUX_REQUEST::NONE);

		this_thread::sleep_for(chrono::seconds(SLEEP_TIME));
	}

	return 0;
}
