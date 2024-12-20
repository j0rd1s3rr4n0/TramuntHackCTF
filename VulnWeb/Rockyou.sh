apt install Python
python -c "with open('rockyou.txt', 'wb') as out: [out.write(open(f'rockyou.txt_parte{i+1}', 'rb').read()) for i in range(NUMERO_PARTES)]"
