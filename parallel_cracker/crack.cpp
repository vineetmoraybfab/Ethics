#include <bits/stdc++.h>
#include <mpi.h>
#include <cln/integer.h>
#include <cln/string.h>
#include <cln/integer_io.h>

int min_len = 1;
int max_len = 20;
std::string symbols("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~`!@#$%^&*()-_=+[{]}|\\;:'\",<.>/?");

//Compile using:
//mpiicxx -DIMPATIENT -std=c++11 -I/path/to/cln/include -L/path/to/cln/lib crack.cpp -lcln

bool process(const std::string &s, const int rank) {

	//std::cout << rank << "- " << s << '\n';
	//return (s == "hello");
	return (s == "!@#4Password");
	return false;
}

int main (int argc, char *argv[]) {

	int rank = -1, size = -1;
	
	MPI_Init(&argc, &argv);
	MPI_Comm_size(MPI_COMM_WORLD, &size);
	MPI_Comm_rank(MPI_COMM_WORLD, &rank);

	try {

		switch (argc) {
		
			case 3 :
				min_len = std::stoi(argv[1]);
				max_len = std::stoi(argv[2]);
				break;
			case 2 : 
				max_len = std::stoi(argv[1]);
				break;
			default :
				break;
		}

	} catch (std::exception e) {
		if (rank == 0) {
			std::cout << "Invalid input : " << e.what();
			std::cout << "\nProceeding with default parameters.....\n";
		}
	}

	if (rank == 0) {
		std::cout << "\nUsing symbols [ " << symbols.length() << " ] : " << symbols;
		std::cout << "\nTrying passwords of length from : [ " << min_len << " - " << max_len << " ]";
		std::cout << "\nUsing processes : " << size << '\n';
	}

	std::string password("");
	int i = max_len + 1;
	for (i = min_len; i <= max_len; ++i) {

		const cln::cl_I possibilities = cln::expt_pos(symbols.length(), i);	//std::pow(symbols.length(), i);
		if (rank == 0) {
			std::cout << "\nAttempting for passwords of length [" << i << "] | Possibilities identified = " << possibilities << '\n';
		}
		int success = 0;

		for (cln::cl_I j = rank; j < possibilities; j += size) {

			std::string s(i, ' ');
			cln::cl_I temp = j;
			for (int k = i - 1; k >= 0; --k) {
				unsigned long index = cln::cl_I_to_ulong( cln::mod(temp, symbols.length()) );
				s[k] = symbols[index];					//symbols[temp % symbols.length()]
				temp = cln::floor1(temp, symbols.length());		//temp /= symbols.length();
			}
			if (process(s, rank)) {
				success = 1 + rank;
				password = s;
				break;
			}
		}

		if (j < possibilities) {
			std::cout << "Rank " << rank << " already found password : " << s << '\n';
			#if defined(IMPATIENT)
			MPI_Abort(MPI_COMM_WORLD, 0);
			#endif
			std::cout << "You can wait for a successful termination or terminate if you are in a hurry.\n";
		}

		int result = 0;
		MPI_Allreduce(&success, &result, 1, MPI_INT, MPI_SUM, MPI_COMM_WORLD);
		//If result is non-zero, some process has the result.
		if (result != 0) {
			//If process 0 has result, no need for any special send-recvs.
			if ((result - 1) != 0) {
				if (rank == 0) {
					char tmp[i];
					//Receive the result answer to process 0 for printing.
					MPI_Recv(tmp, i, MPI_CHAR, result - 1, 0, MPI_COMM_WORLD, MPI_STATUS_IGNORE);
					password = std::string(tmp);
				} else if (success) {
					//If THIS process contains result, send it to process 0.
					MPI_Send(password.c_str(), i, MPI_CHAR, 0, 0, MPI_COMM_WORLD);
				}
			}
			break;
		}
	}

	if (rank == 0) {
		if (i > max_len) {
			std::cout << "\nTried all the possibilities in the problem space.";
			std::cout << "\nNo valid password was found.";
			std::cout << "\nTry increasing the problem space for better brute force.\n";
		} else {
			std::cout << "\nPassword found : " << password << '\n';
		}
	}

	MPI_Finalize();

	return 0;
}
