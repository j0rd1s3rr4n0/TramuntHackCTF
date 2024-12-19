
### Installation

- Install Docker:
```sh
    apt update -y && apt install docker.io
```

### Usage and Install
1. **Clone the repository:**
    ```sh
    git clone https://github.com/j0rd1s3rr4n0/VulnWeb.git
    cd VulnWeb
    ```

2. **Build and run the Docker containers Manually:**
    ```sh
    docker-compose up --build
    ```
2. **Build and run the Docker containers using DockerLabs Method (auto_deploy):**
```
    cd DockerLabs
    chmod +x auto_deploy.sh
    bash ./auto_deploy.sh
```

3. **Access the application:**
    Open your web browser and navigate to `http://localhost:your_port`.